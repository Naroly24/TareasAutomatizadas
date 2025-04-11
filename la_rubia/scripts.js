function buscarCliente() {
    let codigo = document.getElementById('codigo_cliente').value;
    fetch(`buscar_cliente.php?codigo=${codigo}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('nombre_cliente').value = data.nombre || '';
        });
}

function actualizarPrecio(select) {
    let precio = select.options[select.selectedIndex].getAttribute('data-precio');
    let row = select.closest('tr');
    row.querySelector('.precio').value = precio || '';
    calcularTotal(row.querySelector('.cantidad'));
}

function calcularTotal(input) {
    let row = input.closest('tr');
    let cantidad = parseInt(input.value) || 0;
    let precio = parseFloat(row.querySelector('.precio').value) || 0;
    let total = cantidad * precio;
    row.querySelector('.total').value = total.toFixed(2);
    actualizarTotalPagar();
}

function actualizarTotalPagar() {
    let totales = document.querySelectorAll('.total');
    let totalPagar = 0;
    totales.forEach(total => totalPagar += parseFloat(total.value) || 0);
    document.getElementById('total_pagar').textContent = totalPagar.toFixed(2);
    document.getElementById('total_pagar_input').value = totalPagar.toFixed(2);
}

function agregarFila() {
    let tbody = document.getElementById('articulosBody');
    let row = tbody.rows[0].cloneNode(true);
    row.querySelectorAll('input').forEach(input => input.value = '');
    tbody.appendChild(row);
}

function eliminarFila(button) {
    if (document.querySelectorAll('#articulosBody tr').length > 1) {
        button.closest('tr').remove();
        actualizarTotalPagar();
    }
}

function limpiarFormulario() {
    document.getElementById('facturaForm').reset();
    document.getElementById('total_pagar').textContent = '0';
    document.getElementById('articulosBody').innerHTML = document.getElementById('articulosBody').rows[0].outerHTML;
}