var arInput = new Array(0);
var arInputValue1 = new Array(0);
var arInputValue2 = new Array(0);
var arInputValue3 = new Array(0);

function addTextbox() {
    arInput.push(arInput.length);
    arInputValue1.push("");
    arInputValue2.push("");
    arInputValue3.push("");
    _displayTextbox();
}

function deleteTextbox() {
    if (arInput.length > 0) {
        arInput.pop();
        arInputValue.pop();
    }
    _displayTextbox();
}

/*
function _displayTextbox() {
    var html = "";
    html += "<table border='0' cellpadding='5' cellspacing='2'>";
    html += "<tr valign='middle'>";
    html += "<td align='center' class='other-td'>";
    html += "<input name='color[]' type='text' style='ime-mode:active;' size='30'>";
    html += "</td>";
    html += "<td align='center' class='other-td'>";
    html += "<input name='size[]' type='text' style='ime-mode:active;' size='30'>";
    html += "</td>";
    html += "<td align='center' class='other-td'>";
    html += "<input name='stock[]' type='text' style='ime-mode:disabled;' size='10'>";
    html += "</td>";
    html += "</tr>";
    html += "</table>";
    document.getElementById('area_dynamic').innerHTML += html;
}
*/

function _displayTextbox() {
    document.getElementById('area_dynamic').innerHTML = "";
    var html = "";
    for (i = 0; i < arInput.length; i++) {
        html = "";
        html += "<table border='0' cellpadding='5' cellspacing='2'>";
        html += "<tr valign='middle'>";
        html += "<td align='center' class='other-td'>";
        html += "<input name='color[]' type='text' style='ime-mode:active;' onchange='arInputValue1[" + arInput[i] + "]=this.value' value='" + arInputValue1[i] + "' size='30'>";
        html += "</td>";
        html += "<td align='center' class='other-td'>";
        html += "<input name='size[]' type='text' style='ime-mode:active;' onchange='arInputValue2[" + arInput[i] + "]=this.value' value='" + arInputValue2[i] + "' size='30'>";
        html += "</td>";
        html += "<td align='center' class='other-td'>";
        html += "<input name='stock[]' type='text' style='ime-mode:disabled;' onchange='arInputValue3[" + arInput[i] + "]=this.value' value='" + arInputValue3[i] + "' size='10'>";
        html += "</td>";
        html += "</tr>";
        html += "</table>";
        document.getElementById('area_dynamic').innerHTML += html;
    }
}
