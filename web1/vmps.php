<?php
#
# VMPS: vmps.php
#
#  2006.05.25/Sean Boran: Production
#    Remove need for register_globals
#    Add debug1(), validate_webinput
#  2006.01.24/Thomas Dagonnier: First prototype
#
#  Copyright (C) 2006 FreeNAC
#  Licensed under GPL, see LICENSE file or http://www.gnu.org/licenses/gpl.html
##########################

$debug_flag1=false;
#$debug_flag1=true;

include('config.inc');
define_syslog_variables();
openlog("vmps-web", LOG_PID, LOG_LOCAL5);


function print_switch_sel() {
  global $db;
  global $sw;
  $sel = "SELECT * FROM switch";
  $res = mysql_query($sel);
  $html = "<select name=sw>\n";

  while ($swi = mysql_fetch_array($res)) {
    $html .= '<option value="'.$swi['ip'].'"';
    if ($sw == $swi['ip']) {
      $html .= ' selected ';
    };
    $html .='>'.$swi['name'].' ('.$swi['location'].")</option>\n";
  };
  $html .= "</select>\n";
  return($html);
};


function print_dot_sel() {
  global $dottype;
  $types = array('dot','neato','fdp','twopi','circo');
  $html = "<select name=\"dottype\">\n";

  foreach ($types as $mytype) {
    $html .= '<option value="'.$mytype.'"';
    if ($dottype == $mytype) {
      $html .= ' selected ';
    };
    $html .= ">$mytype</option>\n";
  };
  $html .= "</select>\n";
  return($html);
};
	

//-------------- main () -------------------
echo '<head><title>VMPS: query devices per switch</title></head><body>';
vmps_header();
db_connect();

echo "<br>";
echo "List all ports used on the specified switch in the last $vmpsdot_querydays days, and which end-devices were seen on each port. For each end device, the node name and assocated user is shown.<br>";
echo "<br>";
echo "<form method=get action=\"$PHP_SELF\">\n";
echo "Select a switch from the list:<br>";
echo print_switch_sel();
//echo print_dot_sel();
echo "<input type=\"submit\" name=\"submit\" value=\"View\">\n</form>\n";

#$sw=$_REQUEST['sw'];
$sw=validate_webinput($_REQUEST['sw']);
if ($sw) {
  debug1("Calling vmpsdot.php?sw=$sw");
  echo "<img src=\"vmpsdot.php?sw=$sw\" border=0>";
};

vmps_footer();
?>