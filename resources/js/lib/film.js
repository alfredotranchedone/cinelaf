let Film = {


    renderLiveCheck: (data) => {

        let html = '';

        _.each(data,function (v, k) {

            let registiArr = [];
            if(v.regista){
                _.each(v.regista,function (v) {
                    registiArr.push(v.nome + ' ' + v.cognome)
                });
            }
            let registi = '';
            if(registiArr.length > 0){
                registi = registiArr.join(', ');
            }

            html += `
                <div class="list-group-item" data-entry-id="${ v.id }">
                    <h3>${ v.titolo }</h3>
                    <p class="mb-2">
                        <span class="d-inline-block pr-3"> 
                            <i class="fa fa-calendar-alt fa-fw"></i>
                            ${ v.anno }
                        </span>
                        <span>
                            <i class="fa fa-bullhorn fa-fw"></i>
                            ${ registi } 
                        </span>
                    </p>
                    <small>Inserito da ${ v.user.name }</small>
                </div>`;

        });

        return html;

    }



};

module.exports = Film;
