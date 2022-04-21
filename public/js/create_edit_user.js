window.onload = function () {
    let district = document.querySelector('#district');
    let department = document.querySelectorAll('.orgs');
    district.addEventListener('change', function () {
        let sel = document.getElementById("orgns");
        if (sel == null) {
            selector();
        } else {
            let parent = document.getElementById('div1');
            let elem = document.getElementById('orgns');
            parent.removeChild(elem);
            selector();
        }
        function selector() {
            let select = document.createElement("select");
            select.id = "orgns";
            department.forEach(function (el) {
                if (district.value === el.value) {
                    let label = document.createElement("option");
                    label.innerHTML = el.label;
                    select.appendChild(label);
                    let element = document.getElementById("div1");
                    element.appendChild(select);
                }
            })
        }
    })
}
