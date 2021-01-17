<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>...</title>

        <!-- Fonts -->
        <!-- <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"> -->
        <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>


         <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    
         <style>
         #mapid { height: 910px; }
         </style>
         <!-- Make sure you put this AFTER Leaflet's CSS -->
         <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

                
    </head>
    <body>
    <div class="container-fluid">
        <div class="row">
            <nav class="navbar navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand">Safe Crowd</a>

                    <div class="d-flex" >
                        <select class="form-select form-select-sm" aria-label="form-select-sm" style="margin-right: 10px;" id="region">
                                <option selected>Select Region</option>
                                <option value="">Mina</option>
                                <option value="">Arafat</option>
                                <option value="">Muzdalifah</option>
                        </select>

                        <select class="form-select form-select-sm" aria-label="form-select-sm" style="margin-right: 10px;" id="street">
                                <option selected>Select Street</option>
                            @foreach($streets as $street)
                                <option value="{{$street->gid}}">{{$street->gid}}.{{$street->name_ar}}</option>
                            @endforeach
                        </select>

                        <select class="form-select form-select-sm" aria-label="form-select-sm"  style="margin-right: 10px;" id="seqment">
                            <option selected>Select Seqment</option>
                        </select>

                        <select class="form-select form-select-sm" aria-label="form-select-sm"  style="margin-right: 10px;" id="nodeOne">
                            <option selected>Select First Node</option>
                        </select>

                        <select class="form-select form-select-sm" aria-label="form-select-sm" id="nodeTwo">
                            <option selected>Select Second Node</option>
                        </select>

                    </div>
                
                </div>
            </nav>
            
            <div id="mapid"></div>
        </div>
    </div>
    

    
<script>

// document.addEventListener('DOMContentLoaded', () => {
   
//     var mymap = L.map('mapid').setView([51.505, -0.09], 13);


// L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
//     attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
//     maxZoom: 18,
//     id: 'mapbox/streets-v11',
//     tileSize: 512,
//     zoomOffset: -1,
//     accessToken: 'your.mapbox.access.token'
// }).addTo(mymap);



//   });

document.addEventListener("DOMContentLoaded", function () {

    var data = <?=$data?>;
    var counter = 1;
    var colorCounter = 0;
    var oldLong;
    var oldLat;
    var firstpolyline;
    var pointA;
    var pointB;
    var pointList;
   
    var colorArray = [ "#FF6633", "#FFB399", "#FF33FF", "#FFFF99", "#00B3E6", 
            "#E64D66", "#4DB380", "#FF4D4D", "#99E6E6", "#6666FF",
            "#E6B333", "#3366E6", "#999966", "#99FF99", "#B34D4D",
            "#80B300", "#809900", "#E6B3B3", "#6680B3", "#66991A", 
            "#FF99E6", "#CCFF1A", "#FF1A66", "#E6331A", "#33FFCC",
            "#66994D", "#B366CC", "#4D8000", "#B33300", "#CC80CC", 
            "#66664D", "#991AFF", "#E666FF", "#4DB3FF", "#1AB399",
            "#E666B3", "#33991A", "#CC9999", "#B3B31A", "#00E680", 
            "#4D8066", "#809980", "#E6FF80", "#1AFF33", "#999933",
            "#FF3380", "#CCCC00", "#66E64D", "#4D80CC", "#9900B3", 
            "#63b598", "#ce7d78", "#ea9e70", "#a48a9e", "#c6e1e8", "#648177" ,"#0d5ac1" ,
            "#f205e6" ,"#1c0365" ,"#14a9ad" ,"#4ca2f9" ,"#a4e43f" ,"#d298e2" ,"#6119d0",
            "#d2737d" ,"#c0a43c" ,"#f2510e" ,"#651be6" ,"#79806e" ,"#61da5e" ,"#cd2f00" ,
            "#9348af" ,"#01ac53" ,"#c5a4fb" ,"#996635","#b11573" ,"#4bb473" ,"#75d89e" ,
            "#2f3f94" ,"#2f7b99" ,"#da967d" ,"#34891f" ,"#b0d87b" ,"#ca4751" ,"#7e50a8" ,
            "#c4d647" ,"#e0eeb8" ,"#11dec1" ,"#289812" ,"#566ca0" ,"#ffdbe1" ,"#2f1179" ,
            "#935b6d" ,"#916988" ,"#513d98" ,"#aead3a", "#9e6d71", "#4b5bdc", "#0cd36d",
            "#250662", "#cb5bea", "#228916", "#ac3e1b", "#df514a", "#539397", "#880977",
            "#f697c1", "#ba96ce", "#679c9d", "#c6c42c", "#5d2c52", "#48b41b", "#e1cf3b",
            "#5be4f0", "#57c4d8", "#a4d17a", "#225b8", "#be608b", "#96b00c", "#088baf",
            "#f158bf", "#e145ba", "#ee91e3", "#05d371", "#5426e0", "#4834d0", "#802234",
            "#6749e8", "#0971f0", "#8fb413", "#b2b4f0", "#c3c89d", "#c9a941", "#41d158",
            "#fb21a3", "#51aed9", "#5bb32d", "#807fb", "#21538e", "#89d534", "#d36647",
            "#7fb411", "#0023b8", "#3b8c2a", "#986b53", "#f50422", "#983f7a", "#ea24a3",
            "#79352c", "#521250", "#c79ed2", "#d6dd92", "#e33e52", "#b2be57", "#fa06ec",
            "#1bb699", "#6b2e5f", "#64820f", "#1c271", "#21538e", "#89d534", "#d36647",
            "#7fb411", "#0023b8", "#3b8c2a", "#986b53", "#f50422", "#983f7a", "#ea24a3",
            "#79352c", "#521250", "#c79ed2", "#d6dd92", "#e33e52", "#b2be57", "#fa06ec",
            "#1bb699", "#6b2e5f", "#64820f", "#1c271", "#9cb64a", "#996c48", "#9ab9b7",
            "#06e052", "#e3a481", "#0eb621", "#fc458e", "#b2db15", "#aa226d", "#792ed8",
            "#73872a", "#520d3a", "#cefcb8", "#a5b3d9", "#7d1d85", "#c4fd57", "#f1ae16",
            "#8fe22a", "#ef6e3c", "#243eeb", "#1dc18", "#dd93fd", "#3f8473", "#e7dbce",
            "#421f79", "#7a3d93", "#635f6d", "#93f2d7", "#9b5c2a", "#15b9ee", "#0f5997",
            "#409188", "#911e20", "#1350ce", "#10e5b1", "#fff4d7", "#cb2582", "#ce00be",
            "#32d5d6", "#17232", "#608572", "#c79bc2", "#00f87c", "#77772a", "#6995ba",
            "#fc6b57", "#f07815", "#8fd883", "#060e27", "#96e591", "#21d52e", "#d00043",
            "#b47162", "#1ec227", "#4f0f6f", "#1d1d58", "#947002", "#bde052", "#e08c56",
            "#28fcfd", "#bb09b", "#36486a", "#d02e29", "#1ae6db", "#3e464c", "#a84a8f",
            "#911e7e", "#3f16d9", "#0f525f", "#ac7c0a", "#b4c086", "#c9d730", "#30cc49",
            "#3d6751", "#fb4c03", "#640fc1", "#62c03e", "#d3493a", "#88aa0b", "#406df9",
            "#615af0", "#4be47", "#2a3434", "#4a543f", "#79bca0", "#a8b8d4", "#00efd4",
            "#7ad236", "#7260d8", "#1deaa7", "#06f43a", "#823c59", "#e3d94c", "#dc1c06",
            "#f53b2a", "#b46238", "#2dfff6", "#a82b89", "#1a8011", "#436a9f", "#1a806a",
            "#4cf09d", "#c188a2", "#67eb4b", "#b308d3", "#fc7e41", "#af3101", "#ff065",
            "#71b1f4", "#a2f8a5", "#e23dd0", "#d3486d", "#00f7f9", "#474893", "#3cec35",
            "#1c65cb", "#5d1d0c", "#2d7d2a", "#ff3420", "#5cdd87", "#a259a4", "#e4ac44",
            "#1bede6", "#8798a4", "#d7790f", "#b2c24f", "#de73c2", "#d70a9c", "#25b67",
            "#88e9b8", "#c2b0e2", "#86e98f", "#ae90e2", "#1a806b", "#436a9e", "#0ec0ff",
            "#f812b3", "#b17fc9", "#8d6c2f", "#d3277a", "#2ca1ae", "#9685eb", "#8a96c6",
            "#dba2e6", "#76fc1b", "#608fa4", "#20f6ba", "#07d7f6", "#dce77a", "#77ecca"];

    
     mymap = L.map('mapid').setView([21.414955523605258, 39.8889414557457], 14); 
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mymap);


    


    var markerGroup = L.layerGroup().addTo(mymap);
    var lineGroup = L.layerGroup().addTo(mymap);
    var nodsGroup = L.layerGroup().addTo(mymap);

    $( '#street' ).change(function(){
        
        // var _token = $('input[name="_token"]').val();
        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });
        streetId = $(this).val()
        $("#seqment").html('');

        $.ajax({
            url:"street/"+streetId,
            method:"GET",
            dataType: "json",
            success: function( data ) {
                $("#nodeOne").text("");
                $("#nodeTwo").text("");
                $("#nodeOne").append('<option value=' + null + '>' + 'Select First Node' + '</option>');
                // $("#nodeOne").append('<option value=' + 1 + '>' + 1 + '</option>');
                $("#nodeTwo").append('<option value=' + null + '>' + 'Select Second Node' + '</option>');
                $("#seqment").append('<option value=' +null+ '>' + 'Select Seqment' + '</option>');

                markerGroup.clearLayers();
                lineGroup.clearLayers();
                nodsGroup.clearLayers();
                console.log( data );
                $.each(data.segments, function(key , node){
                    $("#seqment").append('<option value=' + node.gid + '>' + node.gid + '</option>');
                })
                
                
                $.each(data.streets, function(key , node){

                    var sequence = node.node_sequence

                    if(sequence == "1"){

                        var pointA = new L.LatLng(node.long, node.lat);
                        var pointB = new L.LatLng(node.long, node.lat);

                        oldLong = node.long
                        oldLat = node.lat

                        return;

                    }else{


                        var pointA = new L.LatLng(oldLong, oldLat);
                        var pointB = new L.LatLng(node.long, node.lat);
                    }

                    var pointList = [pointA, pointB];

                        firstpolyline = new L.polyline(pointList, {
                        color: 'red',
                        weight: 4,
                        opacity: 2,
                        smoothFactor: 6

                    });
                    
                    firstpolyline.addTo(markerGroup);
                    firstpolyline.bindPopup("I am a polygon." + node.node_sequence );

                    oldLong = node.long
                    oldLat = node.lat
                    counter++

                    



                });

                mymap.setView([oldLong, oldLat])


            }
    });
    $( '#seqment' ).change(function(){
 
            gid = $( '#seqment' ).val();
            counter = 1;
            
            $.ajax({
                url:"seqment/"+ gid ,
                method:"GET",
                dataType: "json",
                success: function( data ) {
                    $("#nodeOne").text("");
                    $("#nodeTwo").text("");
                    $("#nodeOne").append('<option value=' + null + '>' + 'Select First Node' + '</option>');
                    $("#nodeTwo").append('<option value=' + null + '>' + 'Select Second Node' + '</option>');
                    
                    lineGroup.clearLayers();
                    nodsGroup.clearLayers();
                    console.log(data)
                     window.seqData = data.nodes;
                    $.each(data.nodes, function(key , node){

                        


                        // if(node.node_sequence % 2){

                        // }else{
                        //     $("#nodeTwo").append('<option value=' + node.gid + '>' + node.node_sequence + '</option>');

                        // }
                        var sequence = node.node_sequence
                        
                        if(sequence == 1 ){

                                pointA = new L.LatLng(node.long, node.lat);
                                pointB = new L.LatLng(node.long, node.lat);
                                oldLong = node.long
                                oldLat = node.lat
                                firstpolyline.bindTooltip("node_path/"+node.node_path +"--"+ "gid/"+node.gid +"--"+"node_sequence/"+node.node_sequence );

                                mymap.setView([node.long, node.lat])


                        }else{


                             pointA = new L.LatLng(oldLong, oldLat);
                             pointB = new L.LatLng(node.long, node.lat);

                        }

                        pointList = [pointA, pointB];

                        firstpolyline = new L.polyline(pointList, {
                            color: colorArray[Math.floor(Math.random() * colorArray.length)],
                            weight: 7,
                            opacity: 2,
                            smoothFactor: 6
                        });



                        // console.log("node_path/"+node.node_path +"--"+ "gid/"+ node.gid +"--"+"node_sequence/"+ node.node_sequence)
                        // firstpolyline.bindTooltip("node_path/"+node.node_path +"--"+ "gid/"+node.gid +"--"+"node_sequence/"+node.node_sequence );
                        // var ifream = `<iframe width="305" height="300" src="https://www.youtube-nocookie.com/embed/xSEtNqm-ojE?autoplay=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                        // var vid = `<video autoplay width="320" height="240" controls><source src="movie.mp4" type="video/mp4"><source src="movie.ogg" type="video/ogg"></video>`;
                       
                        var photoImg = '<img src="/Happy_Saudi_National_Day.gif" height="290px" width="305px"/>';
                      
                       firstpolyline.bindPopup( "<br>"+`<table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                            <th scope="col">node_path</th>
                                                                            <th scope="col">gid</th>
                                                                            <th scope="col">node_sequence</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                            <th scope="row">`+ node.node_path +`</th>
                                                                            <td>`+node.gid +`</td>
                                                                            <td>`+node.node_sequence +` </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>` +"<br>"+ photoImg);


                        firstpolyline.addTo(lineGroup);

                        oldLong = node.long
                        oldLat = node.lat



                        // if(counter == window.seqData.length){

                        //     console.log('last vale' +"/"+ counter +"/"+  window.seqData.length);

                        //     return;

                        // }

                        //     counter++
                            $("#nodeOne").append('<option value=' + node.node_id + '>' + node.node_sequence + '</option>');

  

                    });

                }
            });
    });
    $( '#nodeOne' ).change(function(){
 
        
        gid = $( '#street' ).val();
        nodeOne = $( '#nodeOne' ).val();
        // nodeOneText = $( this ).text();
        nodeTwo = $( '#nodeTwo' ).val();
        $( '#nodeTwo' ).text("");
        $("#nodeTwo").append('<option value=' + null + '>' + 'Select Second Node' + '</option>');
        
        
        // console.log(nodeOneText)
        console.log(window.seqData)
        $.each(window.seqData, function(key , node){
            $("#nodeTwo").append('<option value=' + node.node_id + '>' + node.node_sequence + '</option>');

            if( parseInt(nodeOne)  <  parseInt(node.node_sequence)){

                $("#nodeTwo").append('<option value=' + node.node_id + '>' + node.node_sequence + '</option>');

            }
          
        });

        // window.carName
        // $("#nodeTwo").append('<option value=' + node.gid + '>' + node.node_sequence + '</option>');

        // if(nodeOne != 'null' && nodeTwo != 'null'){
           
        //    seqment();
        // }

    });
    $( '#nodeTwo' ).change(function(){
 
        gid = $( '#street' ).val();
        nodeOne = $( '#nodeOne' ).val();
        nodeTwo = $( '#nodeTwo' ).val();

        if(nodeOne != 'null' && nodeTwo != 'null'){
           
           seqment(gid, nodeOne, nodeTwo);
        }

    });


    function  seqment (gid, nodeOne, nodeTwo){
        counter = 1;
   
            $.ajax({
                url:"node/"+ gid +"/"+ nodeOne +"/"+ nodeTwo,
                method:"GET",
                dataType: "json",
                success: function( data ) {
                    console.log(data);
                    nodsGroup.clearLayers();
                    let prefPoint = null
                    $.each(data.all_nodes, function(key , node){

                        var pointB = new L.LatLng(node.long, node.lat);
                        if(prefPoint){

                            var pointList = [prefPoint, pointB];
                           
                            firstpolyline = new L.polyline(pointList, {
                                color: 'blue',
                                weight: 7,
                                opacity: 2,
                                smoothFactor: 5

                            });

                            firstpolyline.addTo(nodsGroup);
                            // firstpolyline.bindPopup("I am a polygon.xxx" );
                            // mymap.setView([node.long, node.lat])

                            counter++;
                            // END FOR
                        }
                        
                        prefPoint = pointB

                    });
                }
            });
    }


    function  segmentNodes (){

        $.ajax({
            url:"segment-nodes/"+ gid ,
            method:"GET",
            dataType: "json",
            success: function( data ) {
            
                // markerGroup.clearLayers();

                console.log(data);

                var pointA = new L.LatLng(data.f_node.long, data.f_node.lat);
                var pointB = new L.LatLng(data.s_node.long, data.s_node.lat);


                var pointList = [pointA, pointB];

                firstpolyline = new L.polyline(pointList, {
                color: 'blue',
                weight: 4,
                opacity: 2,
                smoothFactor: 6

            });

            firstpolyline.addTo(markerGroup);
            firstpolyline.bindPopup("I am a polygon.xxx" );


            mymap.setView([data.f_node.long, data.f_node.lat])


            }
        });
    }


    function fullSegment(){


    }



});
    


});
  



$( document ).ready(function(){
 
});


</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

    </body>
</html>
