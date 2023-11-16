var crono = class {
    constructor (mod, obj) {
        console.log('crono.js -> constructor');
        this.modulo = mod;
        this.object = obj;
        this.minutos    = document.getElementById("display-minuto");
        this.segundos   = document.getElementById("display-segundo");
        this.centesimas = document.getElementById("display-centesima");
        this.tablaTiempo= document.getElementById("display-parciales");
        this.start = null;
        this.parciales = [];
        this.intervalo = null;
        this.añadirEventos();
    }

    añadirEventos () {
        let me = this;
        $("button[name=inicia-crono]" ).click(function() { me.comienzaCrono (me);  });
        $("button[name=para-crono]"   ).click(function() { me.paraCrono     (me);  });
        $("button[name=reset-crono]"  ).click(function() { me.reiniciaCrono (me);  });
        $("button[name=parcial-crono]").click(function() { me.parcialCrono  (me);  });
        $("form[name=infoSerie] select[name=CALLE]").change(function() {me.buscaInfoSerie (me); });
    }

    comienzaCrono(me) {
        me.start = new Date();
        me.intervalo = setInterval(me.pintaCrono, 10);
        $("button[name=inicia-crono]" ).addClass   ('xx');
        $("button[name=parcial-crono]").removeClass('xx');
        $("button[name=para-crono]"   ).removeClass('xx');
    }

    parcialCrono(me) {
        let actual = new Date();
        me.parciales.push(actual);
        me.pintaParcial(me);
    }

    paraCrono(me) {
        me.parcialCrono(me);
        clearInterval(me.intervalo);
        this.pintaCrono();
        $("button[name=parcial-crono]").addClass   ('xx');
        $("button[name=para-crono]"   ).addClass   ('xx');
        $("button[name=reset-crono]"  ).removeClass('xx');
        let form = me.modulo.Forms['guardaTiempo'];
        form.set(me.modulo.Forms["infoSerie"].get());
        form.set({TIEMPO:((me.parciales[me.parciales.length - 1] - me.start) / 1000) - .005});
        form.executeForm();
    }

    reiniciaCrono (me) {
        me.start = null;
        me.minutos   .innerHTML = padLeft(0);
        me.segundos  .innerHTML = padLeft(0);
        me.centesimas.innerHTML = padLeft(0);
        me.parciales.length = 0;
        $(me.tablaTiempo).empty();
        $("button[name=reset-crono]" ).addClass   ('xx');
        $("button[name=inicia-crono]").removeClass('xx');
    }

    pintaCrono() {
        let me = Moduls.getBody().getScript();
        let actual = (me.intervalo? new Date() : me.parciales[me.parciales.length - 1]);
        let transcurrido = actual - me.start;
        let min = Math.floor((transcurrido / (1000 * 60)) % 60);
        let seg = Math.floor((transcurrido / 1000) % 60);
        let cen = Math.floor((transcurrido / 10) % 100);

        me.minutos   .innerHTML = padLeft(min);
        me.segundos  .innerHTML = padLeft(seg);
        me.centesimas.innerHTML = padLeft(cen);
    }

    pintaParcial(me) {
        let actual = me.parciales[me.parciales.length - 1];
        let transcurridoT = actual - me.start;
        let transcurridoP = actual - (me.parciales.length == 1 ? me.start : me.parciales[me.parciales.length - 2]);
        let minT = Math.floor((transcurridoT / (1000 * 60)) % 60);
        let segT = Math.floor((transcurridoT / 1000) % 60);
        let cenT = Math.floor((transcurridoT / 10) % 100);
        let minP = Math.floor((transcurridoP / (1000 * 60)) % 60);
        let segP = Math.floor((transcurridoP / 1000) % 60);
        let cenP = Math.floor((transcurridoP / 10) % 100);
        let row =
        '<div class="row" style="color: gray;">'+
        '<div style="text-align: end;" class="col col-4">Vuelta '+me.parciales.length+'</div>' +
        '<div style="text-align: center;" class="col col-4">'+padLeft(minP)+':'+padLeft(segP)+'.'+padLeft(cenP)+'</div>' +
        '<div style="text-align: start;" class="col col-4">'+padLeft(minT)+':'+padLeft(segT)+'.'+padLeft(cenT)+'</div>' +
        '</div>';
        me.tablaTiempo.insertAdjacentHTML("beforeend", row);
    }

    buscaInfoSerie (me) {
        me.modulo.Forms["infoSerie"].executeForm();
    }

    infoSerie (s, d, e) {
        if (s) {
            $("span[name=name-competicion]").html(d.root.COM_PISCINA + ': ' + d.root.COM_NOMBRE);
            $("span[name=name-prueba]").html('Prueba ' + d.root.PRU_ORDEN + ': ' + d.root.PRU_DISTANCIA + 'm ' + d.root.PRU_ESTILO);
            $("span[name=name-serie]").html('Serie ' + d.root.SER_ORDEN);
            $("span[name=name-nadador]").html(d.root.SER_NADADOR + ' (' + d.root.SER_CLUB + ')');
            e.form.set({PRUEBA:d.root.SER_PRUEBA, ORDEN:d.root.SER_ORDEN});
        } else {
            validaErroresCBK(d);
        }
    }

    guardaTiempo (s, d, e) {
        if (!s) validaErroresCBK(d);
    }

}

function padLeft (n) {
    var valString = n + "";
    if (valString.length < 2) {
        return "0" + valString;
    } else {
        return valString;
    }
};