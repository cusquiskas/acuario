var header = class {
    constructor (mod, obj) {
        console.log('header.js -> constructor');
        this.modulo = mod;
        this.object = obj;

        this.añadirEventos();
    }

    añadirEventos () {
        let me = this;
        $("button[name=abre-crono]").click(function () {
            Moduls.getBody().load({ url: 'content/crono/crono.html', script: true });
        });
    }

}