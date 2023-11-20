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

    destructor () {
        clearInterval(this.intervalo);
        this.intervalo = null;
    }

    añadirEventos () {
        let me = this;
        me.btnAbrirSerie. click(function () { me.abrirSerie (me, true); });
        me.btnCerrarSerie.click(function () { me.cerrarSerie(me, true); });
    }

    abrirSerie (me, btn) {
        me.intervalo = setInterval(me.actualizaResultados, 1000);
        if (btn) {
            me.modulo.Forms['estadoSerie'].set({"ABIERTA":1});
            me.modulo.Forms['estadoSerie'].executeForm();
        }
        //me.btnAbrirSerie. addClass('xx');
        //me.btnCerrarSerie.removeClass('xx');
    }

    cerrarSerie (me, btn) {
        clearInterval(me.intervalo);
        me.intervalo = null;
        if (btn) {
            me.modulo.Forms['estadoSerie'].set({"ABIERTA":2});
            me.modulo.Forms['estadoSerie'].executeForm();
        }
        //me.btnAbrirSerie. removeClass('xx');
        //me.btnCerrarSerie.addClass('xx');
    }

    actualizaResultados () {
        Moduls.getBody().Forms["listaNadadores"].executeForm();
    }

    actualizaEstadoSerie(estado) {
        if (estado) {
            if (!this.intervalo) this.abrirSerie(this, false);
            this.btnAbrirSerie.addClass('xx');
            this.btnCerrarSerie.removeClass('xx');
        } else {
            if (this.intervalo) this.cerrarSerie(this, false);
            this.btnAbrirSerie.removeClass('xx');
            this.btnCerrarSerie.addClass('xx');
        }
    }

    estadoSerie (s, d, e) {
        if (s) {
            if (e.form.get().ABIERTA == '2') {
                e.form.modul.Forms.listaNadadores.executeForm();
            }
        } else {
            validaErroresCBK(d);
        }
    }

    listaNadadores (s, d, e) {
        let tabla = $("#datosTabla");
        if (!tabla) clearInterval(me.intervalo);
        if (s) {
            tabla.empty();
            let me = e.form.modul.getScript();
            let tr = "<tr><td></td><td>{{CALLE}}</td><td>{{NADADOR}}</td><td>{{CLUB}}</td><td>{{TIEMPO}}</td></tr>";
            for (let i = 0; i < d.root.NADADORES.length; i++) $(tr.reemplazaMostachos(d.root.NADADORES[i])).appendTo(tabla);
            $("span[name=name-piscina]").html('Piscina ' + d.root.COM_PISCINA);
            $("span[name=name-prueba]").html('Prueba ' + d.root.PRU_ORDEN + ': ' + d.root.PRU_DISTANCIA + 'm ' + d.root.PRU_ESTILO);
            $("span[name=name-serie]").html('Serie ' + d.root.SER_ORDEN);
            me.actualizaEstadoSerie((d.root.SER_ABIERTA == 1));
            me.modulo.Forms['estadoSerie'].set({"ORDEN":d.root.SER_ORDEN, "PRUEBA":d.root.PRU_ID, "COMPETICION":d.root.COM_ID});
        } else {
            validaErroresCBK(d);
        }
    }

}