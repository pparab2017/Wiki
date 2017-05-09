var min_zoom = 0.1;
var max_zoom = 7;
var nominal_base_node_size = 8;
var nominal_text_size = 10;
var max_text_size = 24;
var nominal_stroke = 1.5;
var max_stroke = 4.5;
var max_base_node_size = 36;
var text_center = false;
var outline = false;
var tooltipDivID = "mytooltip";
var size = d3.scalePow().exponent(1)
  .domain([1,100])
  .range([8,24]);
//
var zoom = d3.zoom().scaleExtent([min_zoom,max_zoom]);
//getdata(5,20);


function callClick()
{
  d3.select("svg").remove();
 var min = parseInt(document.getElementById("start").value);
 var max = parseInt(document.getElementById("end").value);
if(min=="" | max=="")
{
  alert("Please enter values in Start and End to filter.")
  return 0;
}
if(min>max)
  {
    alert("maximum should be greater then mimimum.")
  return 0;
  }

getdata(parseInt(min),parseInt(max));
}

var ToolTip = {};
ToolTip.make = function (unique_id) {
    var div = document.createElement("div");
    div.setAttribute("id", unique_id);
    div.setAttribute("class", "ToolTip");//style
    return div;
};


function getdata (min, max,data1,data2,type,maxMean)
{
var color1,color2,color3;
var toolTipcolor,border,font_color;
if(type == "Vandal Users")
{
color1 = "#FF8A65";
color2 = "#FDD835";
color3 = "#333";
toolTipcolor = "#FFE082";
border= "#FF8A65";
font_color= "#000";
}
else
{
  color1 = "#009688";
color2 = "#8BC34A";
color3 = "#333";
toolTipcolor = "#8BC34A";
border= "#009688";
font_color = "#fff";
}

var range = [min,max]
var nodes = [];
var links =[];
var toReturn;
var allUsers = [];
var toAdd = 0;
var pageArray = [];
var userPageArray = [];
var userPage =[];
var maxT = 15;
var MaxMean = maxMean;
var svg;
var addedPages = [];


{

allUsers = data1;
userPage = data2;


pageArray = d3.nest()
  .key(function(d) { return d.user; })
  .key(function(d) { return d.pages; })
  .entries(allUsers);
   
   userPageArray = d3.nest()
  .key(function(d) { return d.user; })
  .entries(userPage);


for(var t = 0; t<pageArray.length;t++ ) 
{
  var testObject = pageArray[t];
  console.log(testObject);
  testObject.hasMeta = 0;
  
  for(var i=0; i<testObject.values.length; i++)
  {
  	var o = testObject.values[i];
    console.log(testObject.values[i].key);

    var warCount = 0;
for(var j=0; j<o.values.length; j++)
    {
      if(j>0 & j < (testObject.values.length - 1 ))
    if(o.values[j-1].rev == "True" && o.values[j].rev == "True" )
      {
          warCount++;
      }
    }

    if(testObject.values[i].key.indexOf("Talk") >= 0 || testObject.values[i].key.indexOf("talk") >= 0 )
    {
      //console.log("te");
      if(testObject.values[i].key.indexOf( testObject.key) <= 0)
      testObject.hasMeta = 1;
    }
    else
    {
      if(testObject.hasMeta == 0)
      testObject.hasMeta = 0;
    }
  	var arr = [];
  	for(var j=0; j<o.values.length; j++)
  	{
  		var oo = new Date( o.values[j].time).getTime();
  		arr.push(oo);
  	}
  	testObject.values[i].values = arr;

  	var o2 = testObject.values[i];
  	var timeDiff =[];

	   
  	for(var tf = o2.values.length - 1; tf >= 1; tf-- )
  	{
  		var diff = o2.values[tf] - o2.values[tf -1]
      if(millisToMinutesAndSeconds(diff) < MaxMean)
  		timeDiff.push(millisToMinutesAndSeconds(diff));
  	}
  

    if(warCount > 3 )
  {
   testObject.values[i].war = warCount; 
  }
  else
  {
    testObject.values[i].war = 0; 
  }
  	testObject.values[i].mean = ss.mean(timeDiff);
  	testObject.values[i].values = timeDiff;
	  pageArray[t] = testObject;
  }



}
console.log(pageArray);


  var finalObj = [];
  var numberOfPages= pageArray[0].values.length;
  var userName;
for(var t = 0; t<pageArray.length;t++ ) 
{
  var testObject = pageArray[t];
 var toCheck = pageArray[t].values.length;
  if(toCheck > numberOfPages)
  {
    numberOfPages = toCheck;
    userName = pageArray[t].key;
  }
 var newarr =[];
 for(var i=0; i<testObject.values.length; i++)
  {	
  	if (!( isNaN(testObject.values[i].mean) ))//|| testObject.values[i].mean == 0))
	{
		newarr.push({"key": testObject.values[i].key , "values" : testObject.values[i].values, "mean": testObject.values[i].mean, "war": testObject.values[i].war});
	}
  else
  {
    newarr.push({"key": testObject.values[i].key , "values" : testObject.values[i].values, "mean": 0.1, "war": testObject.values[i].war});

  }
}
testObject.values = newarr;
pageArray[t] = testObject;

  if(pageArray[t].values.length > 0 && pageArray[t].values.length < range[1] )
  {
    finalObj.push(pageArray[t])
  }
}
console.log("----fianl----");
console.log(finalObj);


var last = 0;
var ith = 0;
for(var i = 0;i<finalObj.length;i++ )
{
  var toAdd = {"name" : finalObj[i].key, "group": 1, "r": 10,  "hasM" : finalObj[i].hasMeta , "war" : 0};
  nodes.push(toAdd);
  for(var j=0 ; j<finalObj[i].values.length;j++)
  {


    var toAdd2 = {"name" : finalObj[i].values[j].key , "group": 2, "r": 3, "nameMean": finalObj[i].values[j].key + " | Mean: " + Math.round(finalObj[i].values[j].mean) + " Mins",  "hasM" : finalObj[i].hasMeta, "war": finalObj[i].values[j].war};


    {
      var des = (ith + (j)+ 1);
    addthis ={ "key" : finalObj[i].values[j].key, "val" : des };
    addedPages.push(addthis);
    nodes.push(toAdd2);
   
    var toAddEd = {"source": ith , "target": ith + (j)+ 1 ,"weight":1 , "d": finalObj[i].values[j].mean};
  
    links.push(toAddEd);
 
   }
  
   
    last++;
  }
last++;
ith = last;

} 

 toReturn = {"nodes":nodes , "links":links};

 console.log(toReturn);


var width = 1100,
    height = 600

svg = d3.select("#vandalv1").append("svg")
    .attr("width", width)
    .attr("height", height)

  .call(d3.zoom().on("zoom", redraw))  .append('svg:g')
;

function gravity(alpha) {
  return function(d) {
    d.y += (d.cy - d.y) * alpha;
    d.x += (d.cx - d.x) * alpha;
  };
}

function redraw() {

  svg.attr("transform", d3.event.transform);
}

 var json = toReturn;

var force = d3.forceSimulation(json.nodes)
    .force("charge", d3.forceManyBody().strength(-1))
    .force("link", d3.forceLink(links).distance(function(d) { return  d.d > maxT ? 100 : d.d*2 ; }))
    .force("center", d3.forceCenter(width / 2, height / 2));

   
  


console.log(json);
  force
      .nodes(json.nodes)
      .force("link")
      .links(json.links);


  var link = svg.selectAll(".link")
              .data(json.links)
              .enter().append("line")
              .attr("class", "link")
              .style("stroke-width", function(d) { return Math.sqrt(d.weight); });


  var node = svg.selectAll(".node")
      .data(json.nodes)
      .enter().append("g")
      .attr("class", "node")
     .call(d3.drag()
            .on("start",dragstarted)
            .on("drag",dragged)
            .on("end",dragended));
 
 function dragstarted(d)
 { 
    force.restart();
    force.alpha(1.0);
    d.fx = d.x;
    d.fy = d.y;
 }

 function dragged(d)
 {
    d.fx = d3.event.x;
    d.fy = d3.event.y;
 }

 function dragended(d)
 {
    d.fx = null;
    d.fy = null;
    force.alphaTarget(0.1);
 }
      

  node.on("dblclick.zoom", function(d) { d3.event.stopPropagation();
  var dcx = (window.innerWidth/2-d.x*zoom.scale());
  var dcy = (window.innerHeight/2-d.y*zoom.scale());
  zoom.translate([dcx,dcy]);
  g.attr("transform", "translate("+ dcx + "," + dcy  + ")scale(" + zoom.scale() + ")");


});



  var circle =node.append("circle")
      .attr("r",function(d) { return d.r; }) // sdhoul tells somthing?
      .attr("fill",   function(d) { return d.group == 1 ? color1: color2 })
      .style("stroke",function(d) { return d.war == 0 ? "#fff": "#000" });

  circle.on("mouseover",  function(d) {
   
if(d.group == 2)
{
var exists = document.getElementById(tooltipDivID);
    if (exists == null) {
        var div = ToolTip.make(tooltipDivID);
        document.body.appendChild(div);
    }
    var tooltipdiv = document.getElementById(tooltipDivID);


    tooltipdiv.innerHTML = d.nameMean ;
    tooltipdiv.setAttribute('style',
        'top:' + (parseInt(d3.event.pageY) + 10)  + 'px;' +
        'left:' + (parseInt(d3.event.pageX) + 10)  + 'px;' +
        'border: 2px solid ' + border + ';' +
        'border-radius: 2px;'+
        'background-color: ' + toolTipcolor + ";" +
        'padding: 2px;'+
        'opacity: .5;' +
        'font-family: Arial,Helvetica;' +
        'font-size: 12px;' +
        'color: ' + font_color + ";"+
        'position: absolute;');
}
  });

  circle.on("mouseout", function(d) {

var exists = document.getElementById(tooltipDivID);
    if (exists != null) {
        document.body.removeChild(exists);
    }

    });

  var text = node.append("text")
      .attr("dx", 12)
      .attr("dy", ".35em")
      .style("font-size", "10px")
      .style('fill',function(d){ return d.hasM == 1 ? "#000" : color1 })
      .text(function(d) { return d.group == 1 ? d.name : "" })
      //.style('fill', function(d) { return d.group == 1 ? color1: color3 });

  force.on("tick", function() {
    link.attr("x1", function(d) { return d.source.x; })
        .attr("y1", function(d) { return d.source.y; })
        .attr("x2", function(d) { return d.target.x; })
        .attr("y2", function(d) { return d.target.y; });

    node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
  });
};

}


  function checkIfPresent(arr,val)
  {
      var toReturn = false;
      var des = 0;
      var name = "";
      for(var i=0;i < arr.length;i++)
      {
        if(val == arr[i].key)
         {toReturn = true;des=arr[i].val;name= arr[i].key;  break;}
      }
      return {"check": toReturn,"des": des,"name" :name};
      
  }

function millisToMinutesAndSeconds(millis) {
  var minutes = Math.ceil(millis / 60000);
  return minutes;
}