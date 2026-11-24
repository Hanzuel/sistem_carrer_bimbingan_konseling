
window.CareerQuest = window.CareerQuest || {};

window.CareerQuest.appendCommaValuesAsHiddenInputs = function (form, sourceInput, targetFieldName) {
    if (!form || !sourceInput || !sourceInput.value.trim()) return;

    sourceInput.value
        .split(',')
        .map((item) => item.trim())
        .filter(Boolean)
        .forEach((val) => {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = targetFieldName;
            hidden.value = val;
            form.appendChild(hidden);
        });
};