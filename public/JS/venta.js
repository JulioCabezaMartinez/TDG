function eventos(){
    document.getElementById("btn-quitar-venta").addEventListener("click", () => {
        let color_neon=getComputedStyle(document.documentElement).getPropertyValue('--color-borde-neon').trim();
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
                
            }
        });
    });
}

eventos();
