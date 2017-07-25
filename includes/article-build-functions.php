<?php //this contains the functions that will help build articles.


function make_article_link($article_id, $type)
{
  if($type==="interview")
  {
    return '/articles/interview.php?id=' . $article_id ;
  } 
  else if($type==="article")
  {
    return '/articles/article.php?id=' . $article_id;
  }
}


// inserts whitespace with specified height (px)
function insert_whitespace($height)
{
  return  "<div class='row' style='height:{$height}px;'> <div class='col-sm-12'></div> </div>";
}



//makes content surrounded by proper tag
function makeElement($content, $tagType)
{  
      if( is_array($content) ){
        
        foreach($content as $lines){
          $resulting .= $lines;
        } 
        $tagsAdded = "\n<".$tagType.">".$resulting."\n</".$tagType.">";
        return $tagsAdded;

      } else {
        return "\n\t<{$tagType}>{$content}</{$tagType}>";
      } 
}

//makes tag and also adds attribute and value
function makeElementWithAttr($content, $tagType, $attr ,$attrValue)
{
     if( is_array($content) ){
        
        foreach($content as $lines){
          $resulting .= $lines;
        } 
        $tagsAdded = "\n<".$tagType." ".$attr."='".$attrValue.";'>".$resulting."\n</".$tagType.">";
        return $tagsAdded;

      } else {
        return "\n\t<{$tagType} {$attr}='{$attrValue}'>{$content}</{$tagType}>";
      } 
       
}




function new_buildBody($lines, $firstIndex, $lastIndex)
{
  $array = []; //will contain formatted lines
  $prev_had_attr = false; //initializing to false 
  for ($i=$firstIndex; $i<$lastIndex;/*iterate manually*/)
  {   

    if(substr($lines[$i], 0, 2)=='//'){ $i = $i + 1;}

    $nestedCount=0;
    if (strtolower( $lines[$i] ) === 'nested start')
    {
      $prev_had_attr = false; //now false
      $outerAttr = $lines[$i+1];
      $nestedCount=1;
      $k=$i;

      //loop to find the closing 'nested end'
      while($nestedCount!=0)
      {
        $k++;//ends on index of closing 'nested end'
        if(strtolower( $lines[$k] )==='nested start'){$nestedCount++;}
        if(strtolower( $lines[$k] )==='nested end'){$nestedCount--;}
      }

      //start at $i+2 because $i+1 is set as $outerAttr
      $array[] = makeElement(new_buildBody($lines, $i+2, $k), $outerAttr);

      //start reading lines after nested block
      $i=$k+1;
    } 

    elseif (str_word_count($lines[$i])===1)
    {
      $prev_had_attr = false; //now false 
      $array[] = makeElement($lines[$i+1], $lines[$i]);
      $attr = $lines[$i]; //so it remembers in case the next line has same attr
      $i = $i + 2;
    } 

    elseif(strtolower(substr($lines[$i], 0, 7)) === 'literal')
    {
      $prev_had_attr = false; //now false 
      $array[] = substr($lines[$i], 8);
      $i = $i + 1;
    }   

    elseif(strtolower(substr($lines[$i], 0, 10)) === 'whitespace')
    {
      $prev_had_attr = false; //now false 
      $array[] = insert_whitespace(substr($lines[$i], 18));
      $i = $i + 1;
    } 

    elseif(strtolower(substr($lines[$i], 0, 2)) === 'wa')
    {
      $line_exploded = explode(" ", $lines[$i]);
      $attr = $line_exploded[2]; //still have to make sure to hold attr if attr is the same next time
      $attribute_type = $line_exploded[3];
      $tag_type = $line_exploded[1];
      $array[] = makeElementWithAttr($lines[$i+1], $tag_type, $attr, $attribute_type);

      $prev_had_attr = true;      
      $i = $i + 2;
    } 

    elseif ($lines[$i]!== 'nested start' && $lines[$i] !== 'nested end' && str_word_count($lines[$i])>1)
    {
      if($prev_had_attr)
      {
        $array[] = makeElementWithAttr($lines[$i], $tag_type, $attr, $attribute_type);
        $i = $i + 1;
      } 
      else 
      {
        $array[] =  makeElement($lines[$i], $attr);
        $i = $i + 1; // only add 1 here because you didn't take care of it yet (you used same attr)
      }
    } 
  }
  return $array;
}




//cleans string of newlines, returns, binary chars
function cleanString($dirtyString)
{
  $dirtyString = str_replace("\n", "", $dirtyString);
  $dirtyString = str_replace("\r", "", $dirtyString);
  $dirtyString = str_replace("\0", "", $dirtyString);
  $cleanNow = $dirtyString;
  return $cleanNow;
}


function format_date($date_string)
{
  $month = substr($date_string, 5 , 2);
  switch($month)
  {
    case "01":
      $month = "January";  break;
    case "02":
      $month = "February"; break;
    case "03":
      $month = "March";    break;
    case "04":
      $month = "April";    break;
    case "05":
      $month = "May";      break;
    case "06":
      $month = "June";     break;
    case "07":
      $month = "July";     break;
    case "08":
      $month = "August";   break;
    case "09":
      $month = "September";break;
    case "10":
      $month = "October";  break;
    case "11":
      $month = "November"; break;
    case "12":
      $month = "December"; break;
  }

  $day = substr($date_string, 8);

  if($day=="01" || $day=="21" || $day=="31"){
    $day = substr($day,1)."st";
  } else if($day=="02" || $day=="22"){
    $day = substr($day,1)."nd";
  } else if($day=="03" || $day=="23"){
    $day = substr($day,1)."rd";
  } else {
    $day = $day."th";
  }

  $year = substr($date_string, 0 , 4);
  $new_date = $month . " " . $day . ", " . $year; 
  return $new_date;
}

?>