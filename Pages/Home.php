<!DOCTYPE html>
<htmlcontainer-fluid">
<head>
<link rel="stylesheet" href="../css/bootstrap.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="text/javascript" src="../js/NewDowChart.js"></script>
<script type="text/javascript" src="../js/SVGShapes.js"></script>
<script type="text/javascript" src="../js/bubbleChart.js"></script>
<script type="text/javascript" src="../js/scatterPlot.js"></script>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="../js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="http://d3js.org/d3.v4.min.js"></script>
<script type="text/javascript" src = "../js/simple-statistics.js"></script>
<script type="text/javascript" src="../js/dataMappingVandal.js"></script>

<style>


.link {
  stroke: #aaa;
}


.colorDiv
{
    height:12px;
    max-width:12px;
    border-radius:50%;
    
}

.node text {

font-family: Arial,Helvetica;
cursos:pointer;
}

.node circle{
stroke:#fff;
stroke-width:1px;
}

.text{
  font-family: Arial,Helvetica;
}

    .chart rect {
        fill: steelblue;
    }

        .chart rect:hover {
            fill: turquoise;
        }

    .chart .rectM {
        stroke: green;
        stroke-width: 1;
        fill: green;
        fill-opacity: .2;
    }

        .chart .rectM:hover {
            fill: green;
            fill-opacity: .5;
        }

    .chart text {
        font: 10px sans-serif;
    }

    .chart .title {
        font: 15px sans-serif;
    }

    .axis path,
    .axis line {
        fill: none;
        stroke: #000;
        shape-rendering: crispEdges;
    }
    </style>




<script type="text/javascript">




$.ajax({
type: 'POST',
url: '../DataAccess/DataAccess.php',
data: { "GetPieChartData": 2014 },
success: function(out) {
   $('#waitDiv').remove();
  obj = JSON.parse(out);
  p = new DowChart(135, obj, "graph", "280", "280");
  p.Draw();
}
});
   
$.ajax({
type: 'POST',
url: '../DataAccess/DataAccess.php',
data: { "GetYearData": "0" },
  success: function(out) {
  obj = JSON.parse(out);
  getBuble(obj);
}
});
var pieReq= false;

function callRecors(status, name)
{

if (pieReq) { // don't do anything if an AJAX request is pending
       //alert("running");
        return;
    }


 $("#vandalv1").remove();
//$("#vis-container").remove();
 $("#nodeFilter").hide();

$("#scatterFilter").show();
$("#plotNode").show();
$("#scatterCon").show();
$("#userType").val(status); 
  $( "#scatterCon" ).append( "<div class='col-md-12 table' style='height:400px; background:rgba(185,185,185,0.5); position:absolute; z-index:999; border-radius: 4px;' id='waitDiv' >  <img src='../images/pre.gif' style='padding-left:35%; padding-top:7%'/>  </div> " );

  $("#vis-container").remove();
  
$.ajax({
                type: 'POST',
                url: '../DataAccess/RevertsDB.php',
                data: { "GetReverts": status , "GetYearReverts": $("#amount").val()  },
                success: function(out) {
 
      
        $("#scatterCon").append('<div id="vis-container"></div>');
  $('#waitDiv').remove();
        obj = JSON.parse(out);
        var data = obj;
        console.log(data);
         if($("#userType").val() == "Vandal Users")
        scaterPlot(data,'#d19842','#d15342');
        else
        scaterPlot(data,'#42ccd1','#427ad1');


                },
        complete: function() {
            pieReq = false;
        }
            });
   
pieReq = true;
}


$(function() {



$( "#piecon" ).append( "<div class='col-md-12 table' style='height:450px; background:rgba(185,185,185,0.5); position:absolute; z-index:999; border-radius: 4px;' id='waitDiv' >  <img src='../images/loading.gif' style='padding-left:28%; padding-top:40%'/>  </div> " );


var spinner1 =  $( "#spinner1" ).spinner({
      min: 1,
      max: 500
    });

var spinner2 = $( "#spinner2" ).spinner({
      min: 1,
      max: 500
    });

var spinner3 =  $( "#spinner3" ).spinner({
      min: 1,
      max: 500
    });

var spinner4 = $( "#spinner4" ).spinner({
      min: 1,
      max: 500
    });

var spinner5 = $( "#spinner5" ).spinner({
      min: 1,
      max: 500
    });

    $( "#slider-range-max" ).slider({
      range: "max",
      min: 2005,
      max: 2014,
      value: 2014,
      slide: function( event, ui ) {
$("#graph").empty();
$("#TableDiv").empty();

 $("#vandalv1").remove();
$("#vis-container").remove();
 $("#nodeFilter").hide();
$("#scatterFilter").hide();
$("#plotNode").hide();
$("#scatterCon").hide();


$( "#piecon" ).append( "<div class='col-md-12 table' style='height:450px; background:rgba(185,185,185,0.5); position:absolute; z-index:999; border-radius: 4px;' id='waitDiv' >  <img src='../images/loading.gif' style='padding-left:28%; padding-top:40%'/>  </div> " );

          

        $( "#amount" ).val( ui.value );
          $.ajax({
          type: 'POST',
          url: '../DataAccess/DataAccess.php',
          data: { "GetPieChartData": ui.value },
          success: function(out) {

            $('#waitDiv').remove();
          obj = JSON.parse(out);
          p = new DowChart(135, obj, "graph", "280", "280");
          p.Draw();
          }
          });



      }
    });
    $( "#amount" ).val( $( "#slider-range-max" ).slider( "value" ) );

var requestRunning = false;

    $("#filterScatter").click(function(){
 

       if (requestRunning) {
      // alert("running"); // don't do anything if an AJAX request is pending
        return;
    }

$( "#scatterCon" ).append( "<div class='col-md-12 table' style='height:400px; background:rgba(185,185,185,0.5); position:absolute; z-index:999; border-radius: 4px;' id='waitDiv' >  <img src='../images/pre.gif' style='padding-left:35%; padding-top:7%'/>  </div> " );

  $("#vis-container").remove();
$.ajax({
                type: 'POST',
                url: '../DataAccess/RevertsDB.php',
                data: { "GetRevertsLimitPages" : "0","status": $("#userType").val() , "year": $("#amount").val() 
                ,"min": spinner1.spinner( "value" ), "max": spinner2.spinner( "value" ) },
                success: function(out) {
 
      //alert(out);
        $("#scatterCon").append('<div id="vis-container"></div>');
  $('#waitDiv').remove();
        obj = JSON.parse(out);
        var data = obj;
        console.log(data);

        if($("#userType").val() == "Vandal Users")
        scaterPlot(data,'#d19842','#d15342');
        else
        scaterPlot(data,'#42ccd1','#427ad1');

                },
        complete: function() {
            requestRunning = false;
        }
            });

requestRunning = true;

});



var nodeReq = false;

$("#setnode").click(function(){

if(nodeReq)
{
  //alert("tunning");
  return ;
}

var ul = document.getElementById("selectedUsers");
var items = ul.getElementsByTagName("li");
var selectedUsers = "";
for (var i = 0; i < items.length; ++i) {
  selectedUsers = selectedUsers + items[i].innerHTML.replace("'","''") + ",";
}
console.log(selectedUsers);
if(selectedUsers!="")
{

$( "#NodeCon" ).append( "<div class='col-md-12 table' style='height:400px; background:rgba(185,185,185,0.5); position:absolute; z-index:999; border-radius: 4px;' id='waitDiv' >  <img src='../images/load.gif' style='padding-left:37%; padding-top:10%'/>  </div> " );

  $("#vandalv1").remove();





  $.ajax({
                type: 'POST',
                url: '../DataAccess/NodeLinkDB.php',
                data: { "GetNodeLink": selectedUsers ,"status": $("#userType").val() },
                success: function(out) {
                  $("#NodeCon").append('<div id="vandalv1" class="myDiv" style="float:left; clear:left; "></div>');
                   $('#waitDiv').remove();
                  
                    obj = JSON.parse(out); 
                    console.log(obj[0]); 
                    console.log(obj[1]); 

                    if($('#nodeFilter').css('display') == 'none')
                    {
                      
                      getdata(1,500,obj[0],obj[1],$("#userType").val(),240);
                      $("#nodeFilter").show();
                    }
                    else
                    {
                      //alert("l");
                      getdata(spinner3.spinner( "value" ),spinner4.spinner( "value" ),obj[0],obj[1],$("#userType").val(),spinner5.spinner( "value" ));
                    }
                    //nodeFilter
                    
        
      
                },
    error: function(xhr, textStatus, errorThrown){
       alert('request failed');
    }, 
    complete: function ()
    {

      nodeReq = false;
    }
            });

  nodeReq = true;
}
else
{
  alert("Please select user to plot!")
}

});


  } );









</script>

<title>Wiki Visualisation Page </title>
</head>





<body>

<div class="row" style="background-color: #009688  ;color:#fff; box-shadow: inset 1px -6px 9px -6px #333;">
<div class="container">
<div class="col-lg-8" style="padding-bottom:15px"><h1>Wikipedia Benign and Vandal users
 </h1></div>
<div class="col-lg-4" style="padding-bottom:15px">
 <h5> Group Members: Ishita, Nikita, Pushparaj</h5>
</div>
</div>
</div>
<br/>
<div class="container">

<div id="piecon" class="col-lg-4">
  <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
<div id="slider-range-max" class="col-lg-12"></div>
 <div class="col-lg-12 " id="graph"></div>

  <div class="col-lg-12"  id="TableDiv" >
  </div>
 
</div>

<div class="col-lg-6">
<div class="col-lg-6"></div>
  <div class="col-lg-6" style="border:0; color:#f6931f; font-weight:bold;"> Most attacked page Categories</div>
<div class="chart"></div>

</div>
<div class="col-lg-12" id="scatterFilter" style="display:none">
<div class="col-lg-12"> <h3> <input type="text" id="userType" readonly style="border:0; color:#f6931f; font-weight:bold;">
</h3><hr/>
</div>
<div class="col-lg-2">
 <button type="button" id="filterScatter" class="btn btn-primary">Filter scatter</button>

</div>
  <div class="col-lg-10">
    <label for="spinner1">Limit users with:</label>
    <input id="spinner1" style="width:40px" name="value" value="1">
    <label for="spinner2">to :</label>
    <input id="spinner2" style="width:40px" name="value" value="500">
    <label for="spinner2"> pages </label>
   
    </div>

   
</div>

<div  id="scatterCon" class="col-lg-12" style="height:400px;display:none">
  <div id="vis-container" style="height:400px"></div></div>



<div class="col-lg-12">
<div class="col-lg-2" id="plotNode" style="display:none">
  <button type="button" id="setnode" class="btn btn-primary">Plot Node Link</button>
  </div>
  <div class="col-lg-10" id="nodeFilter" style="display:none">
    
       <label for="spinner3">Limit users with:</label>
    <input id="spinner3" style="width:40px" name="value" value="1">
    <label for="spinner4">to :</label>
    <input id="spinner4" style="width:40px" name="value" value="500">
    <label for="spinner4"> pages </label>

    <label for="spinner5">, Max Mean:</label>
    <input id="spinner5" style="width:40px" name="value" value="240">
  </div>

</div>


<div class="col-lg-12" id="NodeCon">
<div id="vandalv1" class="myDiv" style="float:left; clear:left; "></div>
</div>

</div>



</body>
