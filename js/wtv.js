jQuery(document).ready( function() {
    if(wbst_tvs_script_vars.wbst_tvs_onceNotify == 'no'){
        sessionStorage.setItem('wbst_tvs_demo','');
    }
    var wbst_tvs_poppy = sessionStorage.getItem('wbst_tvs_demo');
    var wbst_tvs_today = new Date(wbst_tvs_script_vars.wbst_tvs_today);
    var wbst_tvs_diff  = new Date(new Date(wbst_tvs_script_vars.wbst_tvs_fit_end_time) - wbst_tvs_today);
    var wbst_tvs_days  = Math.ceil(wbst_tvs_diff/1000/60/60/24);
    if(wbst_tvs_days < 0){ wbst_tvs_days = 0}        

    if(wbst_tvs_days == 0){ 
        jQuery("body").html("<div style=\"text-align:center\"><br><br>" + wbst_tvs_script_vars.wbst_tvs_messageNotify + "</div>");
    }

    if(!wbst_tvs_poppy){
        jQuery.notify.addStyle('happyblue', {
          html: "<div><span data-notify-html/></div>",
          classes: {
            base: {
              "background-color": wbst_tvs_script_vars.wbst_tvs_bgNotify,
              "color": wbst_tvs_script_vars.wbst_tvs_colorNotify,
              "padding": "5px"
            }
          }
        });
        
        jQuery.notify('<strong>' + wbst_tvs_script_vars.wbst_tvs_textNotify1 + '</strong><br>' + wbst_tvs_script_vars.wbst_tvs_textNotify2 + ' <strong>' + wbst_tvs_days + '</strong> ' + wbst_tvs_script_vars.wbst_tvs_textNotify3, {
            style: 'happyblue',
            position: wbst_tvs_script_vars.wbst_tvs_positionNotify,
            autoHide: wbst_tvs_script_vars.wbst_tvs_hideNotify,
            autoHideDelay: 5000
        });

        sessionStorage.setItem('wbst_tvs_demo','true');
    }
});