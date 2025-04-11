@echo off
echo Instalando dependencias...
pip install -r requirements.txt

echo Ejecutando pruebas...
python test_la_rubia.py

echo Pruebas completadas. Los reportes están en la carpeta "reports"
pause