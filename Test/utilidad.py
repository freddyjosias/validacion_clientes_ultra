# utilidades.py
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.action_chains import ActionChains
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.support.wait import WebDriverWait
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.desired_capabilities import DesiredCapabilities
import mysql.connector
from mysql.connector import Error
import pyodbc
import pandas as pd
import random
import string

DATABASE_NAME_TABLE = 'data_ultra_procesado_prod'

class Util:

    def data_test(self, estado):
        try:
            conn = self.conexion_db()
            cursor = conn.cursor()
            cursor.execute("SELECT top 30 id_data as id, desc_latitud as latitud, desc_longitud as longitud, 'MIGRACIÓN WIN-ULTRA' as observacion, " +
                           "nro_documento as dni, desc_celular as celular, desc_correo as correo, tipo_documento as tipodoc, " +
                           "tipo_vivienda as tipoviv, representante_tipo_doc as rtipodoc, representante_nro_doc as rdni, " + 
                           "nombres, ape_paterno as paterno, ape_materno as materno, representante_nombres as rnombres, " + 
                           "representante_ape_paterno as rpaterno, representante_ape_materno as rmaterno, " + 
                           "nro_departamento as dpto, nro_piso as piso, tipo_predio as predio, nombre_condominio as condominio, torre_bloque as bloque, desc_oferta, " +
                           f" desc_producto, desc_celular2 as desc_phone, flg_check_nombres FROM {DATABASE_NAME_TABLE} WHERE fec_baja IS NULL AND estado_pedido = 'Activo' AND status_ingreso_venta = {estado} ORDER BY nro_documento ")
            registros = cursor.fetchall()
            cursor.close()
            conn.close()
            return registros
        except Exception as err:
            print(f"Error al conectar al servidor BD: {err}")
            return 0

    def actualizar_config(self, clave, valor):
        self.db_config[clave] = valor

    def conexion_db(self):
        server = '10.1.4.20'   # Cambia por tu host de MySQL
        user = 'PE_OPTICAL_ERP'      # Cambia por tu usuario de MySQL
        port = '1433'
        password = 'Optical123+'  # Cambia por tu contraseña de MySQL
        database = 'PE_OPTICAL_ADM'
        driver = '{ODBC Driver 17 for SQL Server}'

        if( self.tipobd == 1):
            try:
                return pyodbc.connect(f"DRIVER={driver};SERVER={server};DATABASE={database};UID={user};PWD={password}")
            except Exception as err:
                print(f"Error al conectar a SQL: {err}")
                return 0
                
        if( self.tipobd == 0):
            self.db_config = {
                'host': '10.1.4.82',       # Cambia por tu host de MySQL
                'user': 'root',      # Cambia por tu usuario de MySQL
                'port': '13306',
                'password': 'R007w1N0r3',  # Cambia por tu contraseña de MySQL
                'database': 'test' # Cambia por tu base de datos
            }
            self.actualizar_config('host', server)
            self.actualizar_config('user', user)
            self.actualizar_config('port', port)
            self.actualizar_config('password', password)
            self.actualizar_config('database', database)
            return mysql.connector.connect(**self.db_config)
        
    def __init__(self, direccion):
        self.driver = webdriver.Chrome()
        self.driver.get(direccion)
        self.driver.set_window_size(1426, 747)
        self.db_config = {}
        self.tipobd = 1 

    def test_driver(self):
        return self.driver

    def exist_id(self, element):
        # Verifica la existencia del elemento
        elementos = self.driver.find_elements(By.ID, element)
        if len(elementos) > 0:
            return elementos
        return None

    def exist_css(self, element):
        # Verifica la existencia del elemento
        elementos = self.driver.find_elements(By.CLASS_NAME, element)
        if len(elementos) > 0:
            return elementos
        return None
    
    def wait_id(self, element):
        return WebDriverWait(self.driver, 9999).until(EC.presence_of_element_located((By.ID, element)))
    
    def wait_id_clic(self, element):
        element = self.wait_id(element)
        actions = ActionChains(self.driver)
        actions.move_to_element( element ).perform()
        element.click()
        return element
  
    def wait_name(self, element):
        return WebDriverWait(self.driver, 9999).until(EC.presence_of_element_located((By.NAME, element)))
  
    def wait_name_clic(self, element):
        element = self.wait_name(element)
        actions = ActionChains(self.driver)
        actions.move_to_element( element ).perform()
        element.click()
        return element

    def wait_css(self, element):
        return WebDriverWait(self.driver, 9999).until(EC.presence_of_element_located((By.CSS_SELECTOR, element)))
    
    def wait_css_clic(self, element):
        element = self.wait_css(element)
        actions = ActionChains(self.driver)
        actions.move_to_element( element ).perform()
        element.click()
        return element
  
    def cerrar_prueba(self, estado, text, id):
        try:
            conn = self.conexion_db()
            cursor = conn.cursor()
            cursor.execute(f"UPDATE {DATABASE_NAME_TABLE} SET updated_at = GETDATE(), status_ingreso_venta = " + str(estado) + ", " +
                           "status_resultado = '" + str(text) + "' WHERE id_data = " + str(id) )
            afected_rows = cursor.rowcount
            conn.commit()
            cursor.close()
            conn.close()
            return afected_rows
        except Exception as err:
            print(f"Error al conectar a MySQL: {err}")
            return 0
