ymaps.ready(init);
var myMap,
    myPlacemark;
function init(){
    myMap = new ymaps.Map("map", {
        center: [50.41867144, 30.51710987],
        zoom: 15
    });
    myPlacemark = new ymaps.Placemark([50.418293, 30.516187], {
        hintContent: 'Салон солнцезащитных систем «Salon-SS»',
        balloonContent: 'Салон солнцезащитных систем «Salon-SS». г.Киев, ул.Казимира Малевича 86-Д (на перекрестке улиц Боженко и Байковой, у Аптеки)'
    });
    myMap.geoObjects.add(myPlacemark);
    myMap.behaviors.disable('scrollZoom');
}