function changeHeaderClass(type){
    if(type == 'jal'){
        document.getElementsByClassName('h2-bg')[0].className = "container-fluid header-in jal";
    }
    if(type == 'vert'){
        document.getElementsByClassName('h2-bg')[0].className = "container-fluid header-in vert";
    }
    if(type == 'horiz'){
        document.getElementsByClassName('h2-bg')[0].className = "container-fluid header-in horiz";
    }
    if(type == 'roll'){
        document.getElementsByClassName('h2-bg')[0].className = "container-fluid header-in roll";
    }
    if(type == 'day_night'){
        document.getElementsByClassName('h2-bg')[0].className = "container-fluid header-in day_night";
    }
    if(type == 'plisse'){
        document.getElementsByClassName('h2-bg')[0].className = "container-fluid header-in plisse";
    }
    if(type == 'mosquito'){
        document.getElementsByClassName('h2-bg')[0].className = "container-fluid header-in mosquito";
    }
    if(type == 'out'){
        document.getElementsByClassName('h2-bg')[0].className = "container-fluid header-in out";
    }
}