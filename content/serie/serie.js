var serie = class {
    constructor (mod, obj) {
        console.log('serie.js -> constructor');
        this.modulo = mod;
        this.object = obj;
        this.intervalo = null;
        this.btnAbrirSerie  = $('button[name=abrir-serie]' );
        this.btnCerrarSerie = $('button[name=cerrar-serie]');
        this.añadirEventos();
    }

    añadirEventos () {
        let me = this;
        me.btnAbrirSerie. click(function () { me.abrirSerie (me); });
        me.btnCerrarSerie.click(function () { me.cerrarSerie(me); });
    }

    abrirSerie (me) {
        me.intervalo = setInterval(me.actualizaResultados, 1000);
        me.btnAbrirSerie. addClass('xx');
        me.btnCerrarSerie.removeClass('xx');
    }

    cerrarSerie (me) {
        clearInterval(me.intervalo);
        me.btnAbrirSerie. removeClass('xx');
        me.btnCerrarSerie.addClass('xx');
    }

    actualizaResultados () {
        Moduls.getBody().Forms["listaNadadores"].executeForm();
    }

    listaNadadores (s, d, e) {
        if (s) {
            let tabla = $("#datosTabla");
            tabla.empty();
            let me = e.form.modul.getScript();
            let tr = "<tr><td></td><td>{{CALLE}}</td><td>{{NADADOR}}</td><td>{{CLUB}}</td><td>{{TIEMPO}}</td></tr>";
            for (let i = 0; i < d.root.NADADORES.length; i++) $(tr.reemplazaMostachos(d.root.NADADORES[i])).appendTo(tabla);
            $("span[name=name-piscina]").html('Piscina ' + d.root.COM_PISCINA);
            $("span[name=name-prueba]").html('Prueba ' + d.root.PRU_ORDEN + ': ' + d.root.PRU_DISTANCIA + 'm ' + d.root.PRU_ESTILO);
            $("span[name=name-serie]").html('Serie ' + d.root.SER_ORDEN);
            if (d.root.SER_ABIERTA == 0) { me.btnAbrirSerie.removeClass('xx'); }
            if (d.root.SER_ABIERTA == 1) { me.abrirSerie(me); }
        } else {
            validaErroresCBK(d);
        }
    }

}