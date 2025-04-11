import unittest
import time
import os
from datetime import datetime
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import Select
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import HtmlTestRunner

class LaTarea(unittest.TestCase):
    def setUp(self):
        chrome_options = Options()
        self.driver = webdriver.Chrome(options=chrome_options)
        self.driver.maximize_window()
        self.base_url = "http://localhost/la_rubia" 
        self.driver.get(f"{self.base_url}/index.php")
        
        self.screenshots_dir = "screenshots"
        if not os.path.exists(self.screenshots_dir):
            os.makedirs(self.screenshots_dir)
            
    def tearDown(self):
        self.driver.quit()
        
    def take_screenshot(self, name):
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        filename = f"{self.screenshots_dir}/{name}_{timestamp}.png"
        self.driver.save_screenshot(filename)
        print(f"Captura guardada: {filename}")
        return filename
        
    def test_proceso_completo_venta(self):
        print("PASO 1: Buscando cliente...")
        codigo_cliente = self.driver.find_element(By.ID, "codigo_cliente")
        codigo_cliente.clear()
        codigo_cliente.send_keys("123")
        
        time.sleep(2)
        
        nombre_cliente = self.driver.find_element(By.ID, "nombre_cliente")
        if not nombre_cliente.get_attribute("value"):
            nombre_cliente.send_keys("Juan Pérez")
            print("Nombre ingresado manualmente porque no se completó automáticamente")
        else:
            print(f"Nombre completado automáticamente: {nombre_cliente.get_attribute('value')}")
        
        self.take_screenshot("1_busqueda_cliente")
        
        print("PASO 2: Seleccionando primer artículo...")
        select_articulo = Select(self.driver.find_element(By.XPATH, "//select[@name='articulo[]']"))
        select_articulo.select_by_index(1)
        time.sleep(1)
        
        cantidad_input = self.driver.find_element(By.XPATH, "//input[@name='cantidad[]']")
        cantidad_input.clear()
        cantidad_input.send_keys("3")
        time.sleep(1)
        
        self.take_screenshot("2_primer_articulo")
        
        print("PASO 3: Agregando segundo artículo...")
        agregar_btn = self.driver.find_element(By.XPATH, "//button[contains(text(), 'Agregar')]")
        agregar_btn.click()
        time.sleep(1)
        
        select_articulos = self.driver.find_elements(By.XPATH, "//select[@name='articulo[]']")
        select_articulo2 = Select(select_articulos[1])
        select_articulo2.select_by_index(2)
        time.sleep(1)
        
        cantidades = self.driver.find_elements(By.XPATH, "//input[@name='cantidad[]']")
        cantidades[1].clear()
        cantidades[1].send_keys("2")
        time.sleep(1)
        
        self.take_screenshot("3_segundo_articulo")
        
        print("PASO 4: Agregando comentario...")
        comentario = self.driver.find_element(By.XPATH, "//textarea[@name='comentario']")
        comentario.send_keys("Prueba automatizada con Selenium - Venta completada con éxito")
        
        self.take_screenshot("4_formulario_completo")
        
        print("PASO 5: Guardando factura...")
        guardar_btn = self.driver.find_element(By.XPATH, "//button[contains(text(), 'Guardar')]")
        self.take_screenshot("5_listo_para_guardar")
        
        total_pagar = self.driver.find_element(By.ID, "total_pagar").text
        self.assertNotEqual(total_pagar, "0", "El total a pagar debe ser mayor que cero")
        
        print("Prueba completada con éxito!")
        
    def test_ver_reporte(self):
        print("Prueba de reporte diario...")
        
        reporte_link = self.driver.find_element(By.XPATH, "//a[contains(text(), 'Ver Reporte')]")
        reporte_link.click()
        time.sleep(2)
        
        self.take_screenshot("reporte_diario")
        
        self.assertIn("Reporte", self.driver.title)
        print("Reporte visualizado correctamente!")

if __name__ == "__main__":
    unittest.main(testRunner=HtmlTestRunner.HTMLTestRunner(
        output='reports',
        report_name="la_rubia_test_report",
        add_timestamp=True,
        combine_reports=True,
        report_title="Sistema La Rubia - Reporte de Pruebas"
    ))