<?php
/* 
 * Auteur : David Moreau  et Jie Xing
 *
 *
 */   
 
class Categorie {

  var $base;
  var $hash;
  
  /* Constructeur */
  function Categorie($base) {
      $this->base = $base;
      $result = $base->query('SELECT * FROM categories');
      while($line = mysql_fetch_assoc($result)) {
        $this->hash[$line['id']] = array (
                  'name' => $line['name'],
                  'father_id' => $line['father_id'],
                  'color' => $line['color']
        );
      }     
  }


    function getColor($i) {
	if(isset($this->hash[$i]['color']))
	    return $this->hash[$i]['color'];  
	return -1;
  }
    function getName($i) {
	if(isset($this->hash[$i]['name']))
	    return $this->hash[$i]['name'];  
	return -1;
  }

    function getNames() {
        foreach ($this->hash as $key => $value) {
            $tab[] = $value['name'];
        }
        return $tab;
     }
     
    function getIds() {
        foreach ($this->hash as $key => $value) {
            $tab[] = $key;
        }
        return $tab;
     }

    function getId($name) {
        foreach ($this->hash as $key => $value) {
	    if($value['name'] == $name)
		return $key;
	}
	return -1;
     }
  
  /* for debug only */
  function getHash() {
    return $this->hash;
  }


    function getChildrenR($i,&$tab) {
        foreach ($this->hash as $key => $value) {
            if ($value['father_id'] == $i) {
                $tab[] = $key;
                $this->getChildrenR($key,$tab);
            }
        }
    }

    function getAllChildren($i) {
        $this->getChildrenR($i,$tab);
        return $tab;
    }


    function getFirstChildren($i) {
	foreach ($this->hash as $key => $value) {
            if ($value['father_id'] == $i) {
                $tab[] = $key;
            }
        }
	return $tab;
    } 

    /* Remove a categorie (and children) */
    function remove($id, $base)
    {
	$result = $base->query("DELETE FROM categories where id=$id");
	$tab = $this->getAllChildren($id);
	if ($tab == null)
	    return;
	foreach($tab as $value) {
	    $result = $base->query("DELETE FROM categories where id=$value");
	}
    }


    function getInfosR($id,$name,&$tab) {
        $find = false;
        foreach ($this->hash as $key => $value) {
            if($value['father_id'] == $id) {
                $name2 = $name.'::'.$value['name'];
                $this->getInfosR($key,$name2,$tab);
                $find = true;
            }
        }
        if(!$find) {
            $tab['name'][] = $name;
            $tab['id'][] = $id;
        }
    }

    function getInfos() {
        foreach ($this->hash as $key => $value) {
            if($value['father_id'] == 0) {
                //printf("getInfosR $key %s <br>",$value['name']);
                $this->getInfosR($key,$value['name'],$tab);
            }
        }
        return $tab;
    }


function addInTreeJs(&$code, &$node_id, $cat_id) {
    $father_node_id = $node_id;
    foreach ($this->hash as $key => $value) {
        if($value['father_id'] == $cat_id) {
            $name = $value['name'];
            $color = $value['color'];
            $node_id++;
		    $code .= "d.add($node_id, $father_node_id, '$name', '$key', '$color')\n";
		    $this->addInTreeJs($code, $node_id, $key);
		}
	}
}


    function getTreeJs() {
        $code = "<script type=\"text/javascript\">\n";
	$code .= "<!--\n";
	$code .= "d = new dTree('d');\n";
	$code .= "d.config.inOrder = true;\n";
        $code .= "d.config.folderLinks = true;\n";
        $code .= "d.config.useSelection = true;\n";
        
	$node_id = 0;
	$code .= "d.add($node_id,-1,'dtree');\n";
		
	$this->addInTreeJs($code, $node_id, 0);
        //$code .= "d.closeAll();\n";
	$code .= "document.write(d);\n";

	$code .= "//-->\n";
	$code .= "</script>\n";
	    return $code;
    }




 // fin de la classe
}
?>
