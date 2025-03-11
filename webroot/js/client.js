
    window.onload = (event) => {
        document.getElementById('affiliateYesNo').style.display = 'none';
        document.getElementById('overrideYesNo').style.display = 'none';
        // document.getElementById('editAffiliateYesNo').style.display = 'none';
        // document.getElementById('editOverrideYesNo').style.display = 'none';
    }

    function affiliateYesNo(str) {
        if (str === 'yes') {
            document.getElementById('affiliateYes').checked = true;
            document.getElementById('affiliateYesNo').style.display = '';

        } else {
            document.getElementById('affiliateNo').checked = true;
            document.getElementById('affiliateYesNo').style.display = 'none';
        }
    }

    function overrideYesNo(str) {
        if (str === 'yes') {
            document.getElementById('overrideYes').checked = true;
            document.getElementById('overrideYesNo').style.display = '';
        } else {
            document.getElementById('overrideNo').checked = true;
            document.getElementById('overrideYesNo').style.display = 'none';
        }
    }
    function checkStore(str) {
        if (str === 'yes') {
            document.getElementById('storeYes').checked = true;
            document.getElementById('editstoreYes').checked = true;
        } else {
            document.getElementById('storeNo').checked = true;
            document.getElementById('editstoreNo').checked = true;
        }
    }

    
