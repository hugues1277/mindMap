<!doctype html>
<html lang="fr">
<head>
  <title>MindMap</title>
  <meta charset="utf-8">
	<link rel="shortcut icon" href="script/icon.png" type="image/x-icon" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link rel="stylesheet" type= "text/css" href="script/style.css" />

</head>
<body>
  
<?php 
//ini_set('display_errors', 1);
require_once ('./action.php');
$modeles = afficher_database_table( "mindMap",0 ); // get database mindmap
$nb_modeles = sizeof($modeles); // if nb <3 -> display help infos

if(isset($_GET['id'])){   // get and display mindmap if get id in url
  $idMindMap=$_GET['id'];
  foreach ($modeles as $modele) {
    if($modele['id']==$idMindMap){
      $jsonMap =$modele['json'];
      $name = $modele['name'];
      break;
    }}
  
  echo '<div class="openMapMenu">';
}else{                                  // if no id, display menu
  $jsonMap='{}';
  $idMindMap=0;
  echo '<div class="openMapMenu" style="display: block">';
}
  echo'<h1 style="width:100%; text-align:center;">MindMap</h1>';
echo '<a class="openMapMenuElement openMapMenuElementList" onclick="inserer_mindMap()" style="width:90%; text-align:left; padding-left: 10%;"><div>Create new MindMap</div></a><br><br>';
foreach ($modeles as $modele) { 
  echo '<div class="openMapMenuElementList"><a class="openMapMenuElement"  style="width:75%; text-align:left; padding-left: 5%;" href="index.php?id='.$modele['id'].'"><div onclick="editMode=0">'.$modele['name'].'</div></a>'
    .'<div class="openMapMenuElement" style="width:15%;" onclick="rename_mindMap('.$modele['id'].')">Rename</div>'
    .'<div class="openMapMenuElement" style="width:5%;" onclick="remove_mindMap('.$modele['id'].')">X</div></div>';
  }
echo '</div>';

// color picker table
$colors=["0, 105, 181","84, 199, 236","120, 160, 181","245, 195, 59","243, 83, 105","214, 150, 187","140, 114, 203","185,22,20","66, 183, 42","85, 183, 116","247, 146, 59","230, 133, 133"]; 
?>

<!-- right click menu when bubble is clicked -->  
<div class="rightMenu" id="rightMenuBubble" align="center">                                             
  <div class="bpRightMenuBack" onclick="addBox(event)" ><div class="bpRightMenu bpAdd" ></div></div>
  <div class="bpRightMenuBack" onclick="addLink()" ><div class="bpRightMenu bpLink" ></div></div>
  <div class="bpRightMenuBack" onclick="removeBox()" ><div class="bpRightMenu bpRemove" ></div></div>
  <br>
  <?php
  $i=0;
  foreach($colors as $color){ // color picker
    echo '<div class="bpRightMenuBack bpColor" style="background-color:rgb('.$color.');" onclick="bubbleColor(this.style.backgroundColor )" ></div>';
    $i++;    if($i==6)      echo '<br>';
  }  ?>
</div>
  
<!-- right click menu when line is clicked  -->  
<div class="rightMenu" id="rightMenuLine" align="center">
  <div class="bpRightMenuBack" onclick="removeLine()" ><div class="bpRightMenu bpRemove" ></div></div>
  <div class="bpRightMenu ColorMini" style="display:inline-block;">
    <?php $i=0;
    foreach($colors as $color){ // color picker
      if(!$i){
        echo '<div class="bpRightMenuBack bpColorMini" style="background-color:rgb(0,0,0); border: 1px solid grey; width:8px; height:8px;" onclick="lineColor(this.style.backgroundColor )" ></div>';
        $i++;
      }else{
        echo '<div class="bpRightMenuBack bpColorMini" style="background-color:rgb('.$color.');" onclick="lineColor(this.style.backgroundColor )" ></div>';
      }
    }  ?>
  </div>  
  <br>
  <input type="text" id="nameRightMenuBack" onkeyup="editLegendLine(this.value)" onkeypress="if (event.keyCode==13) quitMenu();">
</div>

  <!--************************************************************************************************************************************-->

<!-- corner menu with save/update/search/... -->
<div class="angle" <?php if(empty($name)){ echo "hidden"; } ?>><?php if(isset($name)){ echo $name; }else{ echo 'MindMap'; } ?></div>
  <div hidden id="rond">	
    <div id="rond0" class="rond" onclick="previous()" title="Previous"><img src="script/img/previous.png"></div>
    <div id="rond1" class="rond" onclick="next()" title="next"><img src="script/img/next.png"></div>
    <div id="rond2" class="rond" onclick="setEditMode()" title="Enable/Disable edition"><img src="script/img/edit.png"></div>
    <div id="rond3" class="rond" onclick="openColorMenu()" title="Color configurator"><img src="script/img/color.png"></div>
    <div id="rond4" class="rond" onclick="openMapMenu()" title="Open menu"><img src="script/img/setting.png"></div>
    <div id="rond5" class="rond" onclick="update_mindMap()" title="Save"><img src="script/img/valid.png"></div>
  
    <div class="arrows" onclick="$('.arrows').fadeOut(500);" hidden>
      <div class="arrow">
        <div class="arrow_text">Enable edit mindMap</div>
        <div class="arrow_body"></div>
      </div>
      <div class="arrow" style="right:110px; top:140px; height: 200px; width:220px;">
        <div class="arrow_text" style="top:170px; right: 150px;">Color configurator</div>
        <div class="arrow_body" style="transform: rotate(180deg);"></div>
      </div>
      <div class="arrow" style="right:50px; top:220px; height: 300px; width:100px;">
        <div class="arrow_text" style="top:265px; right: 60px;">Save modifications</div>
        <div class="arrow_body" style="transform: rotate(180deg);"></div>
      </div>
      <div class="arrow" style="left:31%; top:52px; width: 200px;">
        <div class="arrow_text" style="top:180px; right: -225px;">Search</div>
        <div class="arrow_body" style="transform: rotate(-90deg);"></div>
      </div>
      <div class="arrow" style="left:25%; top:55%;">
        <div class="arrow_text" style="top:260px; right: -60px;">Close details</div>
        <div class="arrow_body" style="transform: rotate(90deg);"></div>
      </div>
      <div class="arrow" style="left:60%; top:60%; width: 200px;">
        <div class="arrow_text" style="top:170px; right: -230px;">rigth click <br>to add bubble</div>
        <div class="arrow_body" style="transform: rotate(-90deg);"></div>
      </div>
    </div>

  </div> 
</div>
<input type="search" id="search" placeholder="Search" val=""></input>
  
  <!--************************************************************************************************************************************-->
  
  
<!-- color menu setting -->  
<div class="colorMenu" align="center">
  
  <div class="colorMenuBody" style=" width:90%; height:105px; position:absolute; top:20px; left:5%;"></div>
  <div class="bubble " style="top:40px; left:10%; min-width:10%;" align="center"><p class="boxtitle" style="color:black; font-size:18px;">Bubble</p></div>
  <div class='formLine' style="top:17px; width:75%; margin:55px 15%;"></div>
  <div class="bubble" style="top:40px; right:10%; min-width:10%;" align="center"><p class="boxtitle" style="color:black; font-size:18px;">Color</p></div>
  
  <div style="width:90%; margin:2% 5%;"><br>
    <h3 style="margin:1%;">Bubble color</h3>
    <?php
    foreach($colors as $color){
      echo '<div class="bpRightMenuBack bpColor" style="background-color:rgb('.$color.');  width:50px; height:50px;" onclick="settingColor(\'bubbleColor\',this.style.backgroundColor )" ></div>';
    }  ?>
    <h3 style="margin:1%;">Line color</h3>
        <div class="bpRightMenuBack bpColor" style="background-color:rgb(0,0,0); border: 1px solid grey; width:50px; height:50px;" onclick="settingColor('lineColor','background-color:rgb(0,0,0)' )" ></div>
        <?php
        foreach($colors as $color){
          echo '<div class="bpRightMenuBack bpColor" style="background-color:rgb('.$color.');  width:50px; height:50px;" onclick="settingColor(\'lineColor\',this.style.backgroundColor )" ></div>';
        }  ?>
  </div>
</div>
  
  <!--************************************************************************************************************************************-->

  
  <div class="message">Welcome to mindMap</div> <!-- msg box -->
  <div class="quitMenu" onclick="quitMenu()" oncontextmenu="quitMenu(); return false"></div> <!-- quitt menu box -->
  
  <iframe  class="iframePage" src=""></iframe> <!-- iframe to display another web site -->
  <div class="iframePageRound rond" onclick="showWebPage()"><img src="script/img/previous.png"></div><!-- return bp -->

  <div class="details" oncontextmenu="/*rightClick(event);*/ return false" align="center"><!-- detail box -->

    <!-- text editor -->    
    <input type="text" class="detailsTitle" onchange="editBubbleTitle(this.id,this.value)" value="" onkeypress="if(event.keyCode==13) document.getElementById('editeur').focus(); ">
    <div class="buttonEdit" >
      <input type="button" value="G" style="font-weight: bold;" onclick="commande('bold');" />
      <input type="button" value="I" style="font-style: italic;" onclick="commande('italic');" />
      <input type="button" value="S" style="text-decoration: underline;" onclick="commande('underline');" />
    
      <input type="button" value="RF" onclick="commande('removeFormat');" />

      <input type="button" value="←" onclick="commande('undo');" /> 
      <input type="button" value="→" onclick="commande('redo');" /> 
      <input type="button" value="Link" onclick="commande('createLink');" />      
      <input type="button" value="Ø" onclick="commande('unlink');" />      

      <select onchange="commande('fontSize', this.value); this.selectedIndex = 0;">
        <option value="">FontSize</option>
        <option value="1px">10</option>
        <option value="2px">12</option>
        <option value="3px">14</option>
        <option value="4px">16</option>
        <option value="5px">18</option>
        <option value="6px">20</option>
        <option value="7px">22</option>
      </select>

      <input type="button" value=">" onclick="commande('indent');" />
      <input type="button" value="<" onclick="commande('outdent');" />
     
      <input type="button" value="puce" onclick="commande('insertunorderedlist');" />
      <div class="colorMenuEdit" style="margin-top:5px;;">
        <?php
        foreach($colors as $color){
          $col=explode(",",$color);
          $hex = "#";
           $hex .= str_pad(dechex($col[0]), 2, "0", STR_PAD_LEFT);
           $hex .= str_pad(dechex($col[1]), 2, "0", STR_PAD_LEFT);
           $hex .= str_pad(dechex($col[2]), 2, "0", STR_PAD_LEFT);
          ?><input type="button" class="bpRightMenuBack bpColor" style="background-color:<?php echo $hex; ?>" onclick="commande('foreColor', '<?php echo $hex; ?>');" ><?php
        }  ?>
      </div>
    </div>
    <div class="closeDetails" onclick="openDetails()"></div> <!-- button close details -->
    <div id="editeur" class="detailsText" contentEditable="true"></div> <!-- textarea with div -->
    <!--textarea class="detailsText" placeholder="Description"></textarea-->
    <input type="text" class="detailsUrl" placeholder="Web site" onchange="editBubbleUrl(this.value)" oninput="editBubbleUrl(this.value)" ><!-- msg box -->
    <div class="bpUrl" onclick="showWebPage()"></div><!-- load url in iframe -->
    <a href="" target="blank" class="Url" hidden ></a>
  </div>
 
  <!-- the mindmap box -->
  <div id="Main" class="mapBox" onmouseup="globalLine(); globalBubble()" oncontextmenu="/*rightClick(event);*/ return false"></div>
  
</body>  
</html>

<script>
//**********************************************************************************************************************************

var jsonMap= <?php echo $jsonMap ?>;  // get json mindMap and his id
var idMindMap= <?php echo $idMindMap ?>;

$( function() {     
  if(idMindMap){
    globalBubbleCreate()
    setId(jsonMap["bubble"][0]["id"])
    $('.angle').click(); openCorner(); $(".quitMenu").fadeOut(10) // display menu
  }
  setTimeout(" window.scrollTo(4500-(screen.width/2),  4200-(screen.height/2));",'50');  
  
  if(<?php echo $nb_modeles; ?> <3 && $(".mapBox").children().length<10){ // display help if it's firts projects
    setTimeout("$('.arrows').fadeIn(500);",'1000');
  }
  
});

window.onbeforeunload = function()   // disable reload of page
{ 
  /*if( $('.openMapMenu').css("display")=="none" && editMode){
    update_mindMap()
    return confirm("Confirm refresh");
  }*/
};  
  
//********************************************************************************************************************************** menu
  
editMode=0 
$(".buttonEdit").css('display','none');
  
function setEditMode(){ // display or not editor buttons for edit mode
  globalLine()
  if(editMode){  
    
    editMode=0;
    $(".buttonEdit").css('display','none');
    update_mindMap() 
    $('.details').attr("oninput","")
    $('.details').find('.detailsText').attr("contentEditable","false")
    $('.details').find('.detailsTitle').attr("readonly","true")
    $('.details').find('.detailsUrl').attr("readonly","true")
    $('.buttonEdit').fadeOut(1000)
    }else{ 
      editMode=1; 
      $(".buttonEdit").css('display','block');
      $('.details').find('.detailsText').attr("contentEditable","true")
      $('.details').find('.detailsTitle').removeAttr("readonly")
      $('.details').find('.detailsUrl').removeAttr("readonly")
      $('.buttonEdit').fadeIn(1000)
    }
  $('.mapBox').empty()  // reset mapbox
  globalBubbleCreate()
  quitMenu()
  $('#search').fadeIn(200)
  setId(jsonMap["bubble"][0]["id"])
}
function openColorMenu(){ // right menu with color
  openDetails(0)
  quitMenu()
  setTimeout("$('.quitMenu').fadeIn(500);",'500');
  $('.colorMenu').fadeIn(200)
  $('.colorMenu').find('.bubble').css('background-color',jsonMap["setting"]["bubbleColor"])
  $('.colorMenu').find('.formLine').css('background-color',jsonMap["setting"]["lineColor"])
}
function settingColor(id,color){  // color menu setting
  openDetails(0)
  jsonMap["setting"][id]=color
  $('.colorMenu').find('.bubble').css('background-color',jsonMap["setting"]["bubbleColor"])
  $('.colorMenu').find('.formLine').css('background-color',jsonMap["setting"]["lineColor"])
}
function openMapMenu(){ // open or not map menu
  openDetails(0)
  quitMenu()
  setTimeout("$('.quitMenu').fadeIn(500);",'500');
  $('.openMapMenu').fadeIn(200)
}
function quitMenu(){  // quit all menus
  $('.rightMenu').fadeOut(200) 
  $(".quitMenu").fadeOut(200)
  $('.colorMenu').fadeOut(200)
  $('.openMapMenu').fadeOut(200)
  $('#search').fadeOut(200)
  quitter_angle()
  globalLine()  
}
open=0;
function openDetails(n){  // open details in left
  initialScrollX = window.pageXOffset || document.documentElement.scrollLeft;
	initialScrollY = window.pageYOffset || document.documentElement.scrollTop;
  if(n){
    if($('.details').css('width')=='0px'  && open)
      $("html, body").animate({ scrollLeft: initialScrollX + window.innerWidth *30/100 }, 'slow');
    $('.details').animate({ 'width':'30%' },'slow');
  	$('.mapBox').animate({ 'left':'30%' },'slow');
    open=1
  }else{
    $('.details').animate({ 'width':'0px' },'slow');
  	$('.mapBox').animate({ 'left':'0px' },'slow');
    if($('.details').css('width')!='0px')
      $("html, body").animate({ scrollLeft: initialScrollX - window.innerWidth *30/100 }, 'slow');
  }
  $('#search').fadeIn(200)
}
function showWebPage(page){ // open url / if dbl click open in new page
  if(page=="dbl"){
    window.open($('.details').find('.Url').attr("href"));
  }else if( $('.iframePage').css("display")!="none" && page !="" ){
    $('.iframePage, .iframePageRound').fadeOut(200)
    $('.iframePage').attr("src","")    
  }else if(page !=""){
    $('.iframePage, .iframePageRound').fadeIn(200)
    $('.iframePage').attr("src",page)    
  }
}

// ******************************************************************************************************** set id
rightMenuId=0
function setId(id){

  rightMenuId=id
  for (var k in jsonMap["bubble"])
    if(jsonMap["bubble"][k]["id"]==id)
      break

  $('.details').attr("oninput","editBubble("+k+")")
  $('.details').find('.detailsTitle').val(decodeURIComponent(jsonMap["bubble"][k]["title"]))
  $('.details').find('.detailsTitle').attr("onkeyup","editBubbleTitle("+id+",this.value)")

  $('.details').find('.detailsText').html(decodeURIComponent(jsonMap["bubble"][k]["text"]))
      
  $('.details').find('.detailsUrl').val(decodeURIComponent(jsonMap["bubble"][k]["url"]))
  if(decodeURIComponent(jsonMap["bubble"][k]["url"])!=''){
    $('.details').find('.Url').attr("href",decodeURIComponent(jsonMap["bubble"][k]["url"]))
    $('.details').find('.bpUrl').attr("onclick","showWebPage('"+decodeURIComponent(jsonMap["bubble"][k]["url"])+"')")
    $('.details').find('.bpUrl').css('display','inline-block')
  }else{
    $('.details').find('.bpUrl').css('display','none')
  }
  
  if(editMode){
    if($('.detailsTitle').val()=="Bubble")
      $('.detailsTitle').select();
    else
      $('.detailsTitle').focus().val("").val(decodeURIComponent(jsonMap["bubble"][k]["title"]));
  }
  openDetails(1)
    
  if(linkStep==1 && idLink!=rightMenuId){
    jsonMap["link"].push({ "begin": idLink,	"end": rightMenuId,	"legend": "",	"color": jsonMap["setting"]["lineColor"] } );
    linkStep=0
    $('.bpLink').css("background-image", "url(script/img/link.png)");
  }
  globalLine()
}  
// ******************************************************************************************************** previous next function

jsonSave=[]
eventChange=0
jsonNextSave=[]
jsonNextId=0
function savePosition(id,n){

  if(!n){
    if(!eventChange){
      delete jsonSave[jsonSave.length-1];
      jsonNextId=jsonNextSave.length
      
    }else{
      eventChange=0
      jsonNextId=jsonNextSave.length
    }
    for (var k in jsonMap["bubble"]){
      if(jsonMap["bubble"][k]["id"]==id){
        jsonSave.push(JSON.parse(JSON.stringify(jsonMap["bubble"][k])))
      }
    }
  }else{
    for (var k in jsonMap["bubble"]){
      if(jsonMap["bubble"][k]["id"]==id && (jsonSave[jsonSave.length-1]["x"]!=jsonMap["bubble"][k]["x"] || jsonSave[jsonSave.length-1]["y"]!=jsonMap["bubble"][k]["y"]) ){
        eventChange=1
      }
    }
  }
}
function previous(){
  
  for(i=1;i<jsonSave.length+1;i++){
    if(jsonSave[jsonSave.length-i]){
      json = jsonSave[jsonSave.length-i]
      delete jsonSave[jsonSave.length-i]
      flag=0
      if(json["title"]=="Bubble" && json["text"]=="" && json["url"]==""){

        rightMenuId=json["id"]
        removeBox()
        flag=1
      }else{
        for (var k in jsonMap["bubble"]){
          if(jsonMap["bubble"][k]["id"]==json["id"]){

            jsonNextSave.push(JSON.parse(JSON.stringify(jsonMap["bubble"][k])))

            jsonMap["bubble"][k]=json
            $('#'+json["id"]).remove()
            createBubble(json)
            setId(json["id"])
            flag=1
            break
          }
        }
        if(flag==0){
          jsonNextSave.push(JSON.parse(JSON.stringify({"action":"remove", "bubble":json})))
          jsonMap["bubble"].push( json );
          createBubble(json)
          setId(json["id"])
          flag=1
        }
        if(jsonNextId>0 && flag==1)
          jsonNextId--
        break
      }
    }
  }
}
function next(){

  console.log(jsonNextSave)

  if(jsonNextSave[jsonNextId]){
    json = jsonNextSave[jsonNextId]
    jsonNextId++

    if(json["action"]){

      jsonSave.push(JSON.parse(JSON.stringify(json["bubble"])))
      rightMenuId=json["bubble"]["id"]
      removeBox()
    }else{
      flag=0
      for (var k in jsonMap["bubble"]){
        if(jsonMap["bubble"][k]["id"]==json["id"]){
          jsonSave.push(JSON.parse(JSON.stringify(jsonMap["bubble"][k])))
          
          jsonMap["bubble"][k]=json
          $('#'+json["id"]).remove()
          createBubble(json)
          setId(json["id"])
          flag=1
          break
        }
      }
      if(flag==0){
        jsonMap["bubble"].push( json );
        createBubble(json)
        setId(json["id"])
      }
    }    
  }
}
  
// ******************************************************************************************************** right menu
  
function rightClick(event, id){
  if(editMode){
    rightMenuId=id
    $('#rightMenuBubble').css({"left":event.clientX+"px","top":event.clientY+"px"});
    $('#rightMenuBubble').fadeIn(200)
    $(".quitMenu").fadeIn(1000);
  }else{
    afficherMessage("You must enable edition mode");
  }
}  
function rightClickLine(event, id, legend){
  if(editMode){
    rightMenuId=id
    var p = $( "#"+id );
    var position = p.position();
    $('#rightMenuLine').css({"left":event.clientX+"px","top":event.clientY+"px"});
    $('#rightMenuLine').fadeIn(200)
    $('#nameRightMenuBack').val($("#"+rightMenuId).find("p").html());
    $('#nameRightMenuBack').val(legend).select();
    $(".quitMenu").fadeIn(1000);
  }
}  
function removeBox(){
  if($(".mapBox").children().length>1){
    $("#"+rightMenuId).remove();
    for (var k in jsonMap["bubble"]){
      if(jsonMap["bubble"][k]["id"]==rightMenuId){
        delete jsonMap["bubble"][k];
        eventChange=1
        break
        }
    }
  }
  quitMenu()
}
function removeLine(){
  $("#"+rightMenuId).remove();
  for (var k in jsonMap["link"]){
    if(jsonMap["link"][k]["begin"]+jsonMap["link"][k]["end"]==rightMenuId)
      delete jsonMap["link"][k];
  }
  quitMenu()
}
function editLegendLine(text){
  $("#"+rightMenuId).text(text)
  for (var k in jsonMap["link"]){
    if(jsonMap["link"][k]["begin"]+jsonMap["link"][k]["end"]==rightMenuId)
      jsonMap["link"][k]["legend"]=encodeURIComponent(text);
  }
  globalLine()
}
function addBox(event){
  date=new Date()
  var id = date.getUTCMilliseconds()+""+date.getSeconds()+""+date.getMinutes()+""+date.getHours();
  var p = $( "#"+rightMenuId );
  var position = p.position();
  offsety=$('.mapBox').scrollTop()
  offsetx=$('.mapBox').scrollLeft()
  
  component = { "id":id, "x": offsetx+position.left, "y": offsety+position.top, "title": "Bubble", "text": "", "url": "", "color":jsonMap["setting"]["bubbleColor"]}
  jsonMap["bubble"].push( component );
  jsonMap["link"].push({ "begin": id,	"end": rightMenuId,	"legend": "", "color":jsonMap["setting"]["lineColor"] } );
  jsonSave.push(component)
  eventChange=1
  createBubble(component)
  quitMenu()
  setId(id)
}
function bubbleColor(color){
  $("#"+rightMenuId).css('background-color',color);
  for (var k in jsonMap["link"]){
    if(jsonMap["bubble"][k] && jsonMap["bubble"][k]["id"]==rightMenuId){
      jsonMap["bubble"][k]["color"]=color
      break
    }
  }
  quitMenu()
}
function lineColor(color){
  $("#"+rightMenuId).css('background-color',color);
  for (var k in jsonMap["link"]){
    if(jsonMap["link"][k] && jsonMap["link"][k]["begin"]+jsonMap["link"][k]["end"]==rightMenuId){
      jsonMap["link"][k]['color']=color
      break
    }
  }
  quitMenu()
}
  
idLink=0
linkStep=0
function addLink(){
  if(linkStep==0 || idLink==rightMenuId){
    idLink=rightMenuId
    linkStep=1
  }
  quitMenu()
}
// ******************************************************************************************************** edit bubble

function commande(nom, argument) {  // edit html function
  if (typeof argument === 'undefined') {
    argument = '';
  }
  switch (nom) {
    case "createLink":
      argument = prompt("your link ?");
      break;
    case "insertImage":
      argument = prompt("your image ?");
      break;
  }
  document.execCommand(nom, false, argument);
}  
function editBubbleTitle(id,text){
  $('#boxtitle'+id).text(text)
}
function editBubbleUrl(val){
  $('.details').find('.Url').attr("href",val)
  $('.details').find('.bpUrl').attr("onclick","showWebPage('"+val+"')")
  if(val!=''){
    $('.details').find('.bpUrl').css('display','inline-block')
  }else{
    $('.details').find('.bpUrl').css('display','none')
  }
}
function editBubble(k){
  jsonMap["bubble"][k]['title']=encodeURIComponent($('.detailsTitle').val())
  jsonMap["bubble"][k]['text']=encodeURIComponent($('.detailsText').html())
  jsonMap["bubble"][k]['url']=encodeURIComponent($('.detailsUrl').val())
  eventChange=1
}

// ******************************************************************************************************** create bubble and line

function globalBubble(){
  for (var k in jsonMap["bubble"]){
    var p = $( "#"+jsonMap["bubble"][k]["id"] );
    var position = p.position();
    jsonMap["bubble"][k]['x']=position.left;
    jsonMap["bubble"][k]['y']=position.top;
  }
}
function globalBubbleCreate(){
  for (var k in jsonMap["bubble"])
      createBubble(jsonMap["bubble"][k])
  globalLine()
}
function globalLine(){
  $( '.line' ).remove()
  for (var k in jsonMap["link"]){
    if(jsonMap["link"][k])
      moveLine(jsonMap["link"][k]["begin"], jsonMap["link"][k]["end"], jsonMap["link"][k]["legend"], jsonMap["link"][k]["color"])
  }
}
//*******************************************************

function createBubble(component){
  if(component){
    id=component["id"]
  
    box='<div id="'+id+'" class="draggable bubble" style="top:'+component["y"]+'px; left:'+component["x"]+'px; background-color:'+component["color"]+';" onclick="setId(this.id)" oncontextmenu="rightClick(event,this.id); savePosition(this.id,0); return false" onmousedown="handTool=0; savePosition(this.id,0)" onmouseup="handTool=1; globalBubble(); savePosition(this.id,1)" onmouseleave="handTool=1" onmousemove="globalLine()">'
    +'<p id="boxtitle'+id+'" class="boxtitle">'+decodeURIComponent(component["title"])+'</p></div>'
    $('.mapBox').append(box);
  
    if(editMode)
      $( ".draggable" ).draggable({ containment: "parent" });
  }
}
// ******************************************************************************************************** move and create line

function moveLine(begin_id, end_id, legend,color){
      
  var p = $( "#"+begin_id );
  var p2 = $( "#"+end_id );
  if ( p.length && p2.length ) {
    var position = p.position();
    var position2 = p2.position();
    offsety=$('.mapBox').scrollTop()
    offsetx=$('.mapBox').scrollLeft()
    
    x1= position.left+p.width()/2+offsetx
    y1= position.top+p.height()/2+offsety
    x2= position2.left+p2.width()/2+offsetx
    y2= position2.top+p2.height()/2+offsety
    
    $( '.mapBox' ).append(createLine(begin_id, end_id, x1,y1,x2,y2, legend,color));
  }
}
function createLineElement(begin_id, end_id,x, y, length, angle, legend,color) {
  var line = document.createElement("div");
  line.setAttribute("class", "line "+begin_id+" "+end_id);
  line.setAttribute("id", begin_id+end_id);
  line.setAttribute("oncontextmenu", "rightClickLine(event,"+begin_id+end_id+", '"+decodeURIComponent(legend)+"' );  return false");
  var styles = '/*border: 2px solid black;*/ '
             + '/*background: black;*/ '
             + 'width: ' + length + 'px; '
             + 'height: 15px; '
             + '-moz-transform: rotate(' + angle + 'rad); '
             + '-webkit-transform: rotate(' + angle + 'rad); '
             + '-o-transform: rotate(' + angle + 'rad); '  
             + '-ms-transform: rotate(' + angle + 'rad); '  
             + 'position: absolute; '
             + 'z-index: 4; '
             + 'top: ' + y + 'px; '
             + 'left: ' + x + 'px; '
             + 'text-align: center;';

  line.setAttribute('style', styles);  
  if(angle>1.559 && angle<4.7)
    line.innerHTML="<p class='legendLine' style='margin:10px; transform: rotate(180deg);'>"+decodeURIComponent(legend)+"</p><div class='formLine' style='background-color:"+color+";'></div>"
  else
    line.innerHTML="<p class='legendLine' style='margin:10px;'>"+decodeURIComponent(legend)+"</p><div class='formLine' style='background-color:"+color+";'></div>"
  return line;
}
function createLine(begin_id, end_id, x1, y1, x2, y2, legend,color) {
  var a = x1 - x2,
      b = y1 - y2,
      c = Math.sqrt(a * a + b * b);
  var sx = (x1 + x2) / 2,
      sy = (y1 + y2) / 2;
  var x = sx - c / 2,
      y = sy;

  var alpha = Math.PI - Math.atan2(-b, a);
  return createLineElement(begin_id, end_id, x, y, c, alpha, legend,color);
}

// ******************************************************************************************************** mouse wheel
  
ZoomVal=1
function handle(delta, event) {
  
  initialScrollX = window.pageXOffset || document.documentElement.scrollLeft;
	initialScrollY = window.pageYOffset || document.documentElement.scrollTop;

  dX =  event.clientX
  dY =  event.clientY  
  if (delta < 0 && ZoomVal> 0.2){

    ZoomVal-=0.05
    window.scrollTo(initialScrollX - 200 , initialScrollY -200  );
  } 
    
  else if (delta > 0 && ZoomVal<2 ){
    ZoomVal+=0.05
    window.scrollTo(initialScrollX + 200  , initialScrollY + 200  );
  }
  
  if (navigator.userAgent.indexOf('Firefox') != -1) {
   // $('.mapBox').css({ 'MozTransform':'scale('+ZoomVal+')' });    
  }else{
    $('.mapBox').css({ 'zoom': ZoomVal });
  }
}
function wheel(event){
  var delta = 0;
  if (!event) // For IE. 
          event = window.event;
  if (event.wheelDelta) { // IE/Opera. 
          delta = event.wheelDelta/120;
  } else if (event.detail) { // Mozilla case. 
          delta = -event.detail/3;
  }
  if (delta)
          handle(delta, event);
  if (event.preventDefault)
          event.preventDefault();
	event.returnValue = false;
}
  
var main = document.getElementById("Main");
if (main.addEventListener)
  main.addEventListener('DOMMouseScroll', wheel, false); //DOMMouseScroll is for mozilla. 
  main.onmousewheel = wheel;// IE/Opera. 

// ******************************************************************************************************** mouse move

function addEvent(a,b,c,d){(a.addEventListener)?a.addEventListener(b,c,d||!1):a.attachEvent("on"+b,c);}
function removeEvent(a,b,c,d){a.removeEventListener?a.removeEventListener(b,c,d||!1):a.detachEvent("on"+b, c);}

function endMove(){
	removeEvent(document, "mousemove", doMove);
	removeEvent(document, "mouseup", endMove);
}
function doMove(e){
	var dX = initialClickX - e.clientX,
		  dY = initialClickY - e.clientY;
	window.scrollTo(initialScrollX + dX, initialScrollY + dY);
}
  handTool=1
function initMove(e){
	
  if (!handTool) return;
	initialScrollX = window.pageXOffset || document.documentElement.scrollLeft;
	initialScrollY = window.pageYOffset || document.documentElement.scrollTop;

	initialClickX = e.clientX;
	initialClickY = e.clientY;
  

	addEvent(document, "mousemove", doMove);
	addEvent(document, "mouseup", endMove);
}
var main = document.getElementById("Main");
var outilMain = true

$('#Main').mousedown(function(e){
   initMove(e)  
});
 
// ******************************************************************************************************** corner
enableQuitt=1
$('.angle').hover(function(){
		openCorner()
});
$('.angle').click(function(){
  enableQuitt=!enableQuitt
  if($('.colorMenu').css('display')=='none' && $('.openMapMenu').css('display')=='none')
    $(".quitMenu").fadeOut(200)
});
  
$('#rond').mouseleave(function(){
		quitter_angle() 
  if($('.colorMenu').css('display')=='none' && $('.openMapMenu').css('display')=='none')
    $(".quitMenu").css('display','none');
});
function openCorner(){
  $(".quitMenu").fadeIn(500);
		$('#rond').show('fast'); 
  	$('#rond0').animate({ 'right':'220px', 'top':'5px', },'fast');
		$('#rond1').animate({ 'right':'160px', 'top':'5px', },'fast');
		$('#rond2').animate({ 'right':'125px', 'top':'55px', },'fast');
		$('#rond3').animate({ 'right':'70px', 'top':'85px', },'fast');
		$('#rond4').animate({ 'right':'10px', 'top':'100px', },'fast');
		$('#rond5').animate({ 'right':'10px', 'top':'160px', },'fast');
		$('.angle').animate({ padding: "20px 20px 50px 60px"},'fast');
}
function quitter_angle(){
  if(enableQuitt){
    $('#rond1, #rond2, #rond3, #rond4').animate({ 'margin-left':'100%', 'margin-top':'0%', },'fast');
    $('').animate({ 'margin-left':'100%', 'margin-top':'0%', },'fast');
    $('#rond').hide('fast'); 
    $('.angle').animate({ padding: "1% 1% 2% 4%"},'fast');
    $('.angle').animate({ 'background-color': "rgba(0,0,0,0.5)"},'fast');
  }
}  
  
// ******************************************************************************************************** database functions
function inserer_mindMap(){
  
  PromptBox("Nom:",'', function() {  
			var name = cleanHTML($('.PromptBox').val())
      url_name = encodeURIComponent(name)

    date=new Date()
    var id = date.getUTCMilliseconds()+""+date.getSeconds()+""+date.getMinutes()+""+date.getHours();

    if(name!=''){
      $.ajax({
        type: "POST",
        url: "./action.php",
        dataType: "json",
        data: {action:'inserer_mindMap' , name, id, url_name },
        "success": function(response){ afficherMessage(response.result); if(response.result!='No response'){ document.location.href="index.php?id="+response.result; } },
        "error": function(jqXHR, textStatus){ alert('Request failed: ' + textStatus); }
        });	
      }
    })
	}
function update_mindMap(){
			
  id=idMindMap
  json = JSON.stringify(jsonMap);
  $.ajax({
    type: "POST",
    url: "./action.php",
    dataType: "json",
    data: {action:'update_mindMap' , id, json },
    "success": function(response){ if(response.result!='No response'){ afficherMessage(response.result); $('.angle').css({'color':'#0069B5', 'font-size':'1.2em' }); setTimeout("$('.angle').css({'color':'#fff', 'font-size':'1em' })",'1500'); } },
    "error": function(jqXHR, textStatus){ alert('Request failed: ' + textStatus); }
    });	
    quitMenu()
}
function rename_mindMap(id){
  PromptBox("Nouveau Nom:",'', function() {  
    var name = cleanHTML($('.PromptBox').val())
    if(name!=''){
    $.ajax({
      type: "POST",
      url: "./action.php",
      dataType: "json",
      data: {action:'rename_mindMap' , id, name },
      "success": function(response){ afficherMessage(response.result); if(response.result!='No response'){ document.location.href="index.php?id="+response.result; } },
      "error": function(jqXHR, textStatus){ alert('Request failed: ' + textStatus); }
      }); }
  })
}
function remove_mindMap(id){
	ConfirmBox('Are you sure you want to delete this map?', function() {				
    $.ajax({
      type: "POST",
      url: "./action.php",
      dataType: "json",
      data: {action:'remove_mindMap' , id },
      "success": function(response){ afficherMessage(response.result); document.location.href="index.php" },
      "error": function(jqXHR, textStatus){ alert('Request failed: ' + textStatus); }
      });	
  })	
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// messages
  
var timeout1, timeout2
function afficherMessage(message){
	if(message){
    
    $('.message').clearQueue().finish();
    clearTimeout(timeout1);
    clearTimeout(timeout2);
		$('.message').text(message);
		$(".message").animate(  {'top': '0px'},500);
    timeout1 = setTimeout("$('.message').animate(  {'top': '-200px'},400);",'2000');
		timeout2 = setTimeout("$('.message').text(''); $('.message').css('top', '-70px');",'2400');
	}
}

function ConfirmBox(message, yesCallback, noCallback) {
  $('.message').html(message);
	$('.message').append( "<br><button id='btnYes'>Oui</button>&nbsp;&nbsp;&nbsp;&nbsp;<button id='btnNo'>Non</button>" )
	$(".message").animate(  {'top': '0px'},00);
  var dialog = $('#modal_dialog').dialog();
  $('#btnYes').focus()
  
  $('#btnYes').click(function() {
      dialog.dialog('close');
      yesCallback();
    $('.message').animate(  {'top': '-200px'},40);
    $('.message').text(''); 
    $('.message').css('top', '-70px');
  });
  $('#btnNo').click(function() {
    $('.message').animate(  {'top': '-200px'},400);
    setTimeout("$('.message').text(''); $('.message').css('top', '-70px');",'400');
  });
}

function PromptBox(message,value, yesCallback) {
    $('.message').html(message);
	$('.message').append( "<br><input id='PromptBox' class='PromptBox' type='text' onkeypress='if (event.keyCode==13) PromptBoxValid();'>" )
	$('.message').append( "<br><button id='btnYes'>Valider</button>&nbsp;&nbsp;&nbsp;&nbsp;<button id='btnNo'>Annuler</button>" )
	$(".message").animate(  {'top': '0px'},00);
    var dialog = $('#modal_dialog').dialog();
	$('#PromptBox').focus().val("").val(value);

    $('#btnYes').click(function() {
        dialog.dialog('close');
        yesCallback();
		  $('.message').animate(  {'top': '-200px'},40);
		  $('.message').css('top', '-70px');
    });
    $('#btnNo').click(function() {
		  $('.message').animate(  {'top': '-200px'},400);
		  setTimeout("$('.message').text(''); $('.message').css('top', '-70px');",'400');
    });
}
function PromptBoxValid(){
	$('#btnYes').click()
}

  
  //*******************************************************************************************************   search

$( "#search" ).mouseover(function() {
  $("#search").animate({'border-radius':'10%','width':'300px'},500);
});
$( "#search" ).mouseleave(function() {
	if(document.getElementById("search").value=='' && !$( "#search" ).is(":focus") )
    setTimeout(function() { $("#search").stop().animate({'width':'32px','border-radius':'80%'},500); }, 1000);
});
$( "#search" ).focusout(function() {
	if(document.getElementById("search").value=='')
    setTimeout(function() { $("#search").stop().animate({'width':'32px','border-radius':'80%'},500); }, 1000);
});
  var text = document.getElementById("search");
  text.addEventListener('input', function() {
    var search = document.getElementById("search").value;
  	if(search=='')
      setTimeout(function() { $("#search").stop().animate({'width':'32px','border-radius':'80%'},500); }, 1000);
    else
      $("#search").animate({'border-radius':'10%','width':'300px'},500);
    seach(search);  
    
  }, false);

precSearch=''
function seach(search){
  
  var searchReg = new RegExp(search, 'gi');
  
  for (var k in jsonMap["bubble"]){
    str = jsonMap["bubble"][k]["title"]
    if(search==''){
      $("#"+jsonMap["bubble"][k]["id"] ).css('background',jsonMap["bubble"][k]["color"])
    }else{
    if(str.match(searchReg))
      $("#"+jsonMap["bubble"][k]["id"] ).css('background','red')
    else if(str.match(precSearch))
      $("#"+jsonMap["bubble"][k]["id"] ).css('background',jsonMap["bubble"][k]["color"])
    }                                            
  }
  precSearch = searchReg 
}

  
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  
  
function cleanHTML(input) {
  // 1. remove line breaks / Mso classes
  var stringStripper = /(\n|\r| class=(")?Mso[a-zA-Z]+(")?)/g; 
  var output = input.replace(stringStripper, ' ');
  // 2. strip Word generated HTML comments
  var commentSripper = new RegExp('<!--(.*?)-->','g');
  var output = output.replace(commentSripper, '');
  //var tagStripper = new RegExp('<(/)*(strong|html|body|div|object|img|ol|ol|li|ul|fieldset|form||tfoot|thead|th|td|menu|output|audio|video|pre|t|code|meta|link|span|\\?xml:|st1:|o:|font)(.*?)>','gi');
  var tagStripper = new RegExp('<(/)*>','gi');
  // 3. remove tags leave content if any
  output = output.replace(tagStripper, '');
  // 4. Remove everything in between and including tags '<style(.)style(.)>'
  var badTags = ['style', 'script','applet','embed','noframes','noscript'];
  
  for (var i=0; i< badTags.length; i++) {
    tagStripper = new RegExp('<'+badTags[i]+'.*?'+badTags[i]+'(.*?)>', 'gi');
    output = output.replace(tagStripper, '');
  }
  // 5. remove attributes ' style="..."'
  var badAttributes = ['style', 'start'];
  for (var i=0; i< badAttributes.length; i++) {
    var attributeStripper = new RegExp(' ' + badAttributes[i] + '="(.*?)"','gi');
    output = output.replace(attributeStripper, '');
  }
  return output;
}
  
  
</script>



