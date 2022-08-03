const event = Vue.createApp({
    data() {
        return {
            arr_checked_depart: [],
            arr_checked_distr: [],
            org: []
        }
    },
    methods: {
        getStatus: function (e) {
            orgns = document.querySelectorAll('.org');
            for (let k = 0; k < orgns.length; k++) {
                orgns[k].checked = false;
                this.org = [];
            }
            if (e.target.dataset.checker === 'org') {
                orgDepart = document.querySelectorAll('.depart');
                orgDistr = document.querySelectorAll('.distr');
                orgDepart.forEach(function (depart_org) {
                    if (depart_org.dataset.value === e.target.dataset.departid) {
                        depart_org.checked = e.target.checked;
                    }
                })
                orgDistr.forEach(function (distr_org) {
                    if (distr_org.dataset.value === e.target.dataset.distrid) {
                        distr_org.checked = e.target.checked;
                    }
                })
            }
            distr = document.querySelectorAll('.distr');
            if (e.target.dataset.checker === 'distr') {
                this.arr_checked_distr = [];
                for (let i = 0; i < distr.length; i++) {
                    if (distr[i].checked === true) {
                        this.arr_checked_distr.push(distr[i]);
                    }
                }
            }
            depart = document.querySelectorAll('.depart');
            if (e.target.dataset.checker === 'depart') {
                this.arr_checked_depart = [];
                for (let i = 0; i < depart.length; i++) {
                    if (depart[i].checked === true) {
                        this.arr_checked_depart.push(depart[i]);
                    }
                }
            }

            if (this.arr_checked_depart.length !== 0 && this.arr_checked_distr.length !== 0) {
                for (let i = 0; i < this.arr_checked_depart.length; i++) {
                    for (let j = 0; j < this.arr_checked_distr.length; j++) {
                        for (let k = 0; k < orgns.length; k++) {
                            if (orgns[k].dataset.departid === this.arr_checked_depart[i].value && orgns[k].dataset.distrid === this.arr_checked_distr[j].value) {
                                orgns[k].checked = true;
                            }
                        }
                    }
                }
            } else if (this.arr_checked_depart.length !== 0 && this.arr_checked_distr.length === 0) {
                for (let i = 0; i < this.arr_checked_depart.length; i++) {
                    for (let k = 0; k < orgns.length; k++) {
                        if (orgns[k].dataset.departid === this.arr_checked_depart[i].value) {
                            orgns[k].checked = true;
                        }
                    }
                }
            } else if (this.arr_checked_depart.length === 0 && this.arr_checked_distr.length !== 0) {
                for (let j = 0; j < this.arr_checked_distr.length; j++) {
                    for (let k = 0; k < orgns.length; k++) {
                        if (orgns[k].dataset.distrid === this.arr_checked_distr[j].value) {
                            orgns[k].checked = true;
                        }
                    }
                }
            }
            for (let k = 0; k < orgns.length; k++) {
                if (orgns[k].checked === true) {
                    this.org.push(orgns[k]);
                }
            }
        }
    }
})
event.mount('#checkboxes');
