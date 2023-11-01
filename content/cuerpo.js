var cuerpo = class {
    constructor (mod, obj) {
        console.log('cuerpo.js -> constructor');
        this.modulo = mod;
        this.object = obj;

        this.añadirEventos();
    }

    añadirEventos () {
        let me = this;
        $("select").change(function (eve) {
            me.modulo.Forms.filters.executeForm();
        });        
    }

    listaCompeticiones (s, d, e) {
        if (s) {
            let tabla = $("#datosTabla");
            tabla.empty();
            let tr = "<tr><td>{{FECHA}}</td><td>{{PRUEBA}}</td><td>{{NADADOR}}</td><td>{{DISTANCIA}}</td><td>{{ESTILO}}</td><td>{{TIEMPO}}</td><td>{{PISCINA}}</td><td>{{INSTALACION}}</td></tr>";
            for (let i = 0; i < d.root.length; i++) $(tr.reemplazaMostachos(d.root[i])).appendTo(tabla);
        } else {
            validaErroresCBK(d);
        }
    }
}