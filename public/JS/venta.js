/**
 * Asigna eventos a botones relacionados con la gestión de productos en la página de ventas.
 *
 * Eventos incluidos:
 * - Click en `#btn-quitar-venta` para confirmar y eliminar un producto temporalmente.
 * - Click en `#btn-quitarBD-venta` para confirmar y eliminar un producto definitivamente de la base de datos.
 * - Click en `#btn-modificar-venta` para mostrar un modal con el formulario de modificación del producto.
 * - Click en `#btn_modificar` para enviar los datos modificados y actualizar el producto en la base de datos.
 */
function eventos(){
    let color_neon=getComputedStyle(document.documentElement).getPropertyValue('--color-borde-neon').trim();

    document.getElementById("btn-quitar-venta").addEventListener("click", () => {
        
        Swal.fire({
            title: "¿Seguro que quieres eliminar el producto?",
            text: "¡No puedes revertir esto! Para volver a agregarlo, debes subirlo de nuevo.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: color_neon,
            cancelButtonColor: "#808080",
            confirmButtonText: "Si, eliminar",
            cancelButtonText: "Cancelar",
            background: "#2C2C2E",
            color: "#FFFFFF"
            
        }).then((result) => {
            if (result.isConfirmed) {
                let id_producto = document.getElementById("id_producto").value;

                fetch("/AJAX/vaciarProducto",{
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ id_producto: id_producto })
                }).then(response => response.json())
                .then(data => {
                    if (data.result === "ok") {
                        Swal.fire({
                            title: "Producto eliminado",
                            text: data.mensaje,
                            icon: "success",
                            confirmButtonColor: color_neon,
                            background: "#2C2C2E",
                            color: "#FFFFFF"
                        }).then(() => {
                            window.location.href = "/ventas";
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: data.mensaje,
                            icon: "error",
                            confirmButtonColor: color_neon,
                            background: "#2C2C2E",
                            color: "#FFFFFF"
                        });
                    }
                })
                .catch(err => console.log(err));
            }
        });
    });

    document.getElementById("btn-quitarBD-venta").addEventListener("click", () => {
        
        Swal.fire({
            title: "¿Seguro que quieres eliminar el producto de la Base de Datos?",
            text: "¡No puedes revertir esto!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: color_neon,
            cancelButtonColor: "#808080",
            confirmButtonText: "Si, eliminar",
            cancelButtonText: "Cancelar",
            background: "#2C2C2E",
            color: "#FFFFFF"
            
        }).then((result) => {
            if (result.isConfirmed) {
                let id_producto = document.getElementById("id_producto").value;
                let entidad = "productos";

                fetch("/AJAX/eliminarDato",{
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: new URLSearchParams({ id, entidad })
                }).then(response => response.json())
                .then(data => {
                    if (data.result === "ok") {
                        Swal.fire({
                            title: "Producto eliminado",
                            text: "Dato eliminado de la Base de Datos correctamente.",
                            icon: "success",
                            confirmButtonColor: color_neon,
                            background: "#2C2C2E",
                            color: "#FFFFFF"
                        }).then(() => {
                            window.location.href = "/ventas";
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "Error al eliminar el producto de la Base de Datos.",
                            icon: "error",
                            confirmButtonColor: color_neon,
                            background: "#2C2C2E",
                            color: "#FFFFFF"
                        });
                    }
                })
                .catch(err => console.log(err));
            }
        });
    })

    document.getElementById("btn-modificar-venta").addEventListener("click", () => {
        const id = document.getElementById("id_producto").value;
        const entidad = "productos";

        const modal = new bootstrap.Modal(document.getElementById("creacion_modificar_dato"));
        modal.show();

        fetch("/AJAX/datosModificarDato", {
            method: "POST",
            headers: {
            "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({ id, entidad })
        })
        .then(res => res.text())
        .then(data => {
            const json = JSON.parse(data);
            const datos = json["dato"];

            for (let key in datos) {
                const input = document.getElementById(key + "Input");
                if(key === "img_venta") continue;

                if (input) input.value = datos[key];
            }
        });
        
    });

    document.getElementById("btn_modificar").addEventListener("click", () => {
        const entidad = "productos";
        const datos = {};

        document.querySelectorAll("[id$='Input']").forEach(input => {
            const key = input.id.replace("Input", "");
            datos[key] = input.value;
        });

        const formData = new FormData();
        formData.append("datos", JSON.stringify(datos));
        formData.append("entidad", entidad);

        fetch("/AJAX/modificarDato", {
            method: "POST",
            body: formData
        })
            .then(res => res.text())
            .then(() => {
                Swal.fire({
                    icon: "success",
                    title: "Dato modificado con éxito",
                    confirmButtonColor: color_neon,
                    background: "#2C2C2E",
                    color: "#FFFFFF"
                }).then(() => {
                    window.location.href = "/ventas/view?id=" + datos["id"];
                })
            })
            .catch(() => {
                Swal.fire({
                    icon: "error",
                    title: "Error en el servidor",
                    confirmButtonColor: color_neon,
                    background: "#2C2C2E",
                    color: "#FFFFFF"
                });
            });
    });
}

eventos();
