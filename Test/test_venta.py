import time
import os

import matplotlib.pyplot as plt
import pandas as pd
import seaborn as sns
import pyperclip
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys

from utilidad import Util

BASE_URL = "https://appwinforce-win.ultra.pe/login"
CREDENTIALS = {
    "username": "migracion.win.ultra9@ultra.pe",
    "password": "Socio@#$@%"
}

NUEVO_SEGUIMIENTO_URL = "https://appwinforce-win.ultra.pe/nuevoSeguimiento"

CURRENT_DIR = os.path.dirname(os.path.abspath(__file__))

IMAGEN_PATH = os.path.join(CURRENT_DIR, "contrato_generico.pdf")

FECHA_TRAMO = "06-01-2025"
HORA_TRAMO = "12:00"

class TestVenta:

  def setup_method(self, method):
    self.util = Util(BASE_URL)
    self.driver = self.util.test_driver()
    self.registros = self.util.data_test(1)
    self.resultado = ""
    
    #Login
    self.util.wait_id_clic("username").send_keys(CREDENTIALS["username"])
    self.util.wait_id_clic("password").send_keys(CREDENTIALS["password"])
    self.util.wait_id_clic("ingresar")

    time.sleep(3)

  def teardown_method(self, method):
    self.driver.quit()
  
  def test_all_venta(self):
    # Iterar sobre los registros y usarlos en Selenium
    for registro in self.registros:
        # Ejemplo: Rellenar un formulario con los datos de la tabla
        self.latitud = str(registro[1])
        self.longitud = str(registro[2])
        self.dni = registro[4]
        self.celular = registro[5]
        self.correo = registro[6]
        self.tipodoc = registro[7].upper()
        self.tipoviv = registro[8]
        self.nombres = registro[11]
        self.paterno = registro[12]
        self.materno = registro[13]
        self.rdni = registro[10]
        self.rtipodoc = registro[9]
        self.rnombres = registro[14]
        self.rpaterno = registro[15]
        self.rmaterno = registro[16]
        self.dpto = registro[17]
        self.piso = registro[18]
        self.predio = registro[19].capitalize()
        self.condominio = registro[20]
        self.bloque = registro[21]
        self.plan = registro[22]
        self.desc_producto = registro[23]
        self.desc_phone = registro[24]
        self.flg_check_nombres = registro[25]

        self.observacion = registro[3]
        self.id = registro[0]
        self.one_venta()
        print(f"Registro procesado: {registro}")

  def one_venta(self):

    # Menu Nuevo Lead
    self.resultado = ""

    self.util.test_driver().get(NUEVO_SEGUIMIENTO_URL)
    #Nuevo Lead
    self.util.wait_id_clic("btnNuevoLead")
    self.util.wait_id_clic("gf_lat").send_keys(self.latitud)
    self.util.wait_id_clic("gf_lon").send_keys(self.longitud)
    self.util.wait_id_clic("gf_buscar_coordenadas")
    self.util.wait_id("loader-msj")
    self.util.wait_css_clic(".gf_btnPopup")
    self.util.wait_css(".btn-warning-degradate")

    time.sleep(3)

    self.util.wait_id_clic("continuar")

    time.sleep(3)

    #tipo de vivienda 'Multifamiliar'
    try:
      self.util.wait_id("tipo_servicio").find_element(By.XPATH, f"//option[. = '{self.tipoviv}']").click()
    except Exception:
      return 1


    if self.tipoviv != "Hogar":
      self.util.wait_id_clic("piso").send_keys(self.piso + Keys.RETURN)
      self.util.wait_id_clic("num_departament").send_keys(self.dpto + Keys.RETURN)

      if self.tipoviv != "Multifamiliar":
        self.util.wait_id_clic("cond_torre").send_keys(self.bloque + Keys.RETURN)

        if self.tipoviv == "Condominio/Edificio":
          self.util.wait_id_clic("condominio_ws").send_keys(self.condominio + Keys.RETURN)

    self.util.wait_id("relacionPredio").find_element(By.XPATH, f"//option[. = '{self.predio}']").click()
    self.util.wait_id("tipoInteres").find_element(By.XPATH, "//option[. = 'Venta']").click()

    #documento
    self.util.wait_id("tipo_doc").find_element(By.XPATH, f"//option[. = '{self.tipodoc}']").click()
    self.util.wait_id_clic("documento_identidad").send_keys(self.dni)

    dni_ingresado = self.util.wait_id_clic("documento_identidad").get_attribute("value")

    if dni_ingresado != self.dni:
      pyperclip.copy(self.dni)  # Copia el DNI al portapapeles
      self.util.wait_id_clic("documento_identidad").clear()
      self.util.wait_id_clic("documento_identidad").send_keys(Keys.CONTROL + "v")

    self.util.wait_id_clic("search_score_cliente")
    self.resultado = self.util.wait_css(".swal2-title").text

    if self.resultado == "ZONA DE RIESGO":
      self.util.wait_css_clic(".swal2-confirm")
      time.sleep(1)
      self.resultado = self.util.wait_css(".swal2-title").text

    #error/continuar
    time.sleep(4)

    if self.resultado != "Bien..." and self.resultado != "Aviso..." and self.resultado != "ZONA DE RIESGO":
      self.util.cerrar_prueba(5, self.util.wait_css(".swal2-html-container").text, self.id )
      return 1

    #nuevo cliente
    try:
        self.util.wait_id_clic("nuevoCliente")
    except Exception:
        return 1

    time.sleep(1)

    if self.resultado == "Aviso...":
      if self.flg_check_nombres == 1:
        self.util.wait_id("cli_ape_pat").clear()
        self.util.wait_id("cli_ape_mat").clear()
        self.util.wait_id("cli_nom").clear()

        self.util.wait_id("cli_ape_pat").send_keys(self.paterno)
        self.util.wait_id("cli_ape_mat").send_keys(self.materno)
        self.util.wait_id("cli_nom").send_keys(self.nombres)
      else:
        self.util.cerrar_prueba(23, "Error en los nombres", self.id)
        return 0

    self.util.wait_id("cli_tel1").send_keys(self.celular)
    self.util.wait_id("cli_email").send_keys(self.correo)
    self.util.wait_id("cli_tel2").send_keys(self.desc_phone)

    # representante
    try:
      element = self.util.wait_id("datos_representante")  # Cambia el selector
      display = element.value_of_css_property("display")
      visibility = element.value_of_css_property("visibility")
      if display != "none" and visibility != "hidden":
        self.resultado = "representante_legal"
      else:
        self.resultado = ""
    except Exception:
        self.resultado = ""

    if self.resultado == "representante_legal":
      self.util.wait_id_clic("tipo_doc_representante").send_keys( self.rtipodoc + Keys.RETURN )
      self.util.wait_id("documento_identidad_representante").send_keys(self.rdni)

      resultado_eval = ""
      saltar_alert_score = True

      try:
        self.util.wait_id_clic("search_score_cliente_representante")
        saltar_alert_score = False
      except Exception:
        if len(self.util.wait_id("nom_representate").get_attribute("value")) < 10:
          return 1

      if not saltar_alert_score:
          resultado_eval = self.util.wait_css(".swal2-title").text

      #error/continuar
      if resultado_eval == "Lo siento...":
        # time.sleep(2)
        self.util.cerrar_prueba(4, self.util.wait_css(".swal2-html-container").text, self.id )
        self.util.wait_css_clic(".swal2-confirm")
        # time.sleep(3)
        return 1

      try:
        if not saltar_alert_score:
          self.util.wait_css_clic(".swal2-confirm")
      except Exception:
        return 1

      if resultado_eval == "Aviso...":
        if self.flg_check_nombres == 1:
          self.util.wait_id("nom_representate").send_keys(self.rpaterno + " " + self.rmaterno + " " + self.rnombres)
        else:
          self.util.cerrar_prueba(23, "Error en los nombres", self.id)
          return 0

      self.util.wait_id("cli_tel1_representante").send_keys(self.celular)
      self.util.wait_id("cli_email_representante").send_keys(self.correo)
    else:

      elemento_ape_paterno = self.util.wait_id("cli_ape_pat")
      elemento_ape_materno = self.util.wait_id("cli_ape_mat")
      elemento_nombres = self.util.wait_id("cli_nom")

      if (len(elemento_ape_paterno.get_attribute("value")) < 1 or len(elemento_ape_materno.get_attribute("value")) < 1 or
          len(elemento_nombres.get_attribute("value")) < 1):

        if self.flg_check_nombres == 1:
            self.util.wait_id("cli_ape_pat").clear()
            self.util.wait_id("cli_ape_mat").clear()
            self.util.wait_id("cli_nom").clear()

            self.util.wait_id("cli_ape_pat").send_keys(self.paterno)
            self.util.wait_id("cli_ape_mat").send_keys(self.materno)
            self.util.wait_id("cli_nom").send_keys(self.nombres)
        else:
            self.util.cerrar_prueba(23, "Error en los nombres", self.id)
            return 0
    
    self.util.wait_css_clic(".scroll-y")
    self.util.wait_id_clic("add_customer_data")
    resultado_eval = self.util.wait_css(".swal2-title").text

    if resultado_eval != "Bien...":
        # time.sleep(1)
        self.util.cerrar_prueba(2, resultado_eval, self.id )
        self.util.wait_css_clic(".swal2-title")
        # time.sleep(3)
        return 1

    self.util.wait_css_clic(".swal2-confirm")

    #observaciones finales y continuar
    self.util.wait_id_clic("observaciones").send_keys(self.observacion)
    self.util.wait_id_clic("register_search")
    resultado_eval = self.util.wait_css(".swal2-title").text

    if resultado_eval == "Lo siento...":
      self.util.cerrar_prueba(6, self.util.wait_css(".swal2-html-container").text, self.id)
      self.util.wait_css_clic(".swal2-confirm")
      # ime.sleep(2)
      return 1

    self.util.wait_css_clic(".swal2-confirm")
    self.util.wait_id_clic("continuar")

    #planes
    self.util.wait_id_clic("tipoBusqueda").find_element(By.XPATH, "//option[. = 'Internet']").click()
    
    time.sleep(3)

    try:
      self.util.wait_id_clic("filtroOferta").find_element(By.XPATH, f"//option[. = '{self.desc_producto}']").click()
    except Exception:   
      return 1

    self.util.wait_name_clic("plan")
    self.util.wait_id_clic("continuar")

    time.sleep(1)
    #registro final
    self.util.wait_id_clic("select2-venta_origen-container")
    self.util.wait_css_clic(".select2-search__field").send_keys("MIGRACION WIN-ULTRA"  + Keys.RETURN)

    time.sleep(1)

    elemento = self.util.wait_id_clic("tramo_fecha")
    time.sleep(1)
    self.driver.execute_script("arguments[0].removeAttribute('readonly')", elemento)

    elemento.send_keys(FECHA_TRAMO + Keys.RETURN)

    time.sleep(1.5)

    elemento = self.util.wait_id_clic("tramo_horario_rango")



    self.driver.execute_script("arguments[0].removeAttribute('readonly')", elemento)

    elemento.send_keys(HORA_TRAMO + Keys.RETURN)

    self.util.wait_id_clic("select2-como_se_entero-container")
    self.util.wait_css_clic(".select2-search__field").send_keys("MIGRACION WIN-ULTRA"  + Keys.RETURN)
    self.util.wait_id_clic("observacionesVenta").send_keys(self.observacion)
    self.util.wait_name("images[]").send_keys(IMAGEN_PATH)

    time.sleep(2)

    self.util.wait_id_clic("btn_solicitar_ahora")

    time.sleep(2)

    try:
      self.util.wait_css_clic(".swal2-confirm")
    except Exception:
      return 1

    self.util.cerrar_prueba(10, "ok", self.id)
    return 0
  
  def plot(self):

    # Crear un DataFrame de ejemplo
    data = pd.DataFrame({
        'Categoría': ['A', 'B', 'C', 'D'],
        'Valores': [15, 30, 45, 10]
    })

    # Extraer los datos para el gráfico
    sizes = data['Valores']
    labels = data['Categoría']
    colors = sns.color_palette('pastel', len(labels))

    # Crear el gráfico
    plt.figure(figsize=(8, 8))
    plt.pie(sizes, labels=labels, colors=colors, autopct='%1.1f%%', startangle=90)
    plt.title("Gráfico de pastel con datos")
    plt.show()
