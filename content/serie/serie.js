var serie = class {
    constructor (mod, obj) {
        console.log('serie.js -> constructor');
        this.modulo = mod;
        this.object = obj;
        this.intervalo = null;
        this.añadirEventos();
    }

    añadirEventos () {
        let me = this;
        
    }

    abrirSerie (me) {
        me.intervalo = setInterval(me.actualizaResultados, 1000);
    }

    cerrarSerie (me) {
        clearInterval(me.intervalo);
    }

    actualizaResultados () {
        Moduls.getBody().Forms["listaNadadores"].executeForm();
    }

    listaNadadores (s, d, e) {
        if (s) {
            let tabla = $("#datosTabla");
            tabla.empty();
            let tr = "<tr><td></td><td>{{CALLE}}</td><td>{{NADADOR}}</td><td>{{CLUB}}</td><td>{{TIEMPO}}</td></tr>";
            for (let i = 0; i < d.root.length; i++) $(tr.reemplazaMostachos(d.root[i])).appendTo(tabla);
        } else {
            validaErroresCBK(d);
        }
    }

}