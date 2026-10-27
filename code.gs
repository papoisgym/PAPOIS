/* PAPOIS Gym — Google Apps Script backend.
   Este proyecto debe estar vinculado a la hoja de cálculo PAPOIS. */
var HOJAS = ['socios', 'admins', 'pagos', 'asist', 'egresos', 'reservas', 'equipo', 'banco', 'emp'];
var DIAS_AVISO = 5;

function clave_() {
  return PropertiesService.getScriptProperties().getProperty('CLAVE');
}

// Sirve la página principal desde el mismo origen de Apps Script.
function doGet(e) {
  return HtmlService.createTemplateFromFile('index')
    .evaluate()
    .setTitle('PAPOIS Gym');
}

// Incluye bibliotecas locales en la plantilla HTML.
function include(nombre) {
  return HtmlService.createHtmlOutputFromFile(nombre).getContent();
}

// API invocada por google.script.run desde index.html; no usa fetch/CORS.
function apiPAPOIS(peticion) {
  if (!peticion || !clave_() || peticion.clave !== clave_()) {
    return { ok: false, error: 'clave' };
  }

  if (peticion.accion === 'load') {
    return cargar_();
  }

  if (peticion.accion === 'save') {
    var datos = peticion.datos;
    if (!datos || typeof datos !== 'object') {
      return { ok: false, error: 'datos_invalidos' };
    }
    // Evita limpiar hojas si el cliente envía accidentalmente un objeto incompleto.
    for (var i = 0; i < HOJAS.length; i++) {
      if (!Array.isArray(datos[HOJAS[i]])) {
        return { ok: false, error: 'falta_lista', hoja: HOJAS[i] };
      }
    }
    HOJAS.forEach(function(nombre) {
      escribir_(nombre, datos[nombre]);
    });
    return { ok: true };
  }

  return { ok: false, error: 'accion_desconocida' };
}

function cargar_() {
  var ss = SpreadsheetApp.openById('15Vu22BIgvC9oq_XpJaTPYudsSNfvUFZqE_Lz_FGXoTc');
  if (!ss) {
    throw new Error('No se encontró la hoja activa. Abre este proyecto desde Extensiones > Apps Script en PAPOIS.');
  }
  var datos = {};
  HOJAS.forEach(function(nombre) {
    var hoja = ss.getSheetByName(nombre);
    var valores = hoja ? hoja.getDataRange().getValues() : [];
    datos[nombre] = [];
    for (var i = 1; i < valores.length; i++) {
      var fila = {};
      for (var j = 0; j < valores[0].length; j++) {
        var valor = valores[i][j];
        if (typeof valor === 'string' && /^[\[{]/.test(valor)) {
          try { valor = JSON.parse(valor); } catch (err) {}
        }
        if (valor !== '') fila[valores[0][j]] = valor;
      }
      datos[nombre].push(fila);
    }
  });
  return datos;
}
function escribir_(nombre, filas) {
  var ss = SpreadsheetApp.openById('15Vu22BIgvC9oq_XpJaTPYudsSNfvUFZqE_Lz_FGXoTc');
  if (!ss) throw new Error('No se encontró la hoja de cálculo activa.');
  var hoja = ss.getSheetByName(nombre) || ss.insertSheet(nombre);
  hoja.clearContents();
  if (!filas.length) return;


  var columnas = [];
  filas.forEach(function(fila) {
    Object.keys(fila).forEach(function(columna) {
      if (columnas.indexOf(columna) < 0) columnas.push(columna);
    });
  });
  var matriz = [columnas].concat(filas.map(function(fila) {
    return columnas.map(function(columna) {
      var valor = fila[columna];
      if (valor === undefined || valor === null) return '';
      return typeof valor === 'object' ? JSON.stringify(valor) : String(valor);
    });
  }));
  hoja.getRange(1, 1, matriz.length, columnas.length)
    .setNumberFormat('@')
    .setValues(matriz);
}

// Funciones opcionales para aviso de vencimientos por correo.
function notificarVencimientos() {
  var socios = cargar_().socios;
  var hoy = new Date();
  var cambio = false;
  socios.forEach(function(socio) {
    var dias = Math.round((new Date(socio.vence + 'T12:00:00') - hoy) / 864e5);
    if (!socio.email || dias < 0 || dias > DIAS_AVISO || socio.avisado === socio.vence) return;
    MailApp.sendEmail(socio.email, 'Tu membresía PAPOIS está por vencer',
      'Hola ' + socio.nombre + ', tu membresía PAPOIS vence el ' + socio.vence + '. Renuévala en recepción o en la página.');
    socio.avisado = socio.vence;
    cambio = true;
  });
  if (cambio) escribir_('socios', socios);
}

function instalarTrigger() {
  ScriptApp.newTrigger('notificarVencimientos').timeBased().everyDays(1).atHour(9).create();
}


function probarConexion() {
  var ss = SpreadsheetApp.openById('15Vu22BIgvC9oq_XpJaTPYudsSNfvUFZqE_Lz_FGXoTc');

  console.log('Connection OK: ' + ss.getName());

  console.log('Sheets found: ' + ss.getSheets().map(function(h) {
    return h.getName();
  }).join(', '));
}


function probarLectura() {
  var ss = SpreadsheetApp.openById('15Vu22BIgvC9oq_XpJaTPYudsSNfvUFZqE_Lz_FGXoTc');
  var nombres = ['socios', 'admins', 'pagos', 'asist', 'egresos', 'reservas', 'equipo', 'banco', 'emp'];

  nombres.forEach(function(nombre) {
    var hoja = ss.getSheetByName(nombre);
    console.log(nombre + ': ' + (hoja ? 'OK' : 'NO ENCONTRADA'));
  });
}


