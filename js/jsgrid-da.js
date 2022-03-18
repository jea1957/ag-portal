(function(jsGrid) {

    jsGrid.locales.da = {
        grid: {
            noDataContent: "Ingen data fundet",
            deleteConfirm: "Er du sikker?",
            pagerFormat: "Sider: {first} {prev} {pages} {next} {last} &nbsp;&nbsp; {pageIndex} ud af {pageCount}",
            pagePrevText: "<",
            pageNextText: ">",
            pageFirstText: "<<",
            pageLastText: ">>",
            loadMessage: "Vent venligst...",
            invalidMessage: "Ugyldige data angivet!"
        },

        loadIndicator: {
            message: "Henter..."
        },

        fields: {
            control: {
                searchModeButtonTooltip: "Skift til søgning",
                insertModeButtonTooltip: "Skift til indsæt",
                editButtonTooltip: "Ret",
                deleteButtonTooltip: "Slet",
                searchButtonTooltip: "Søg",
                clearFilterButtonTooltip: "Nulstil filter",
                insertButtonTooltip: "Indsæt",
                updateButtonTooltip: "Gem",
                cancelEditButtonTooltip: "Afbryd"
            }
        },

        validators: {
            required: { message: "Felt er påkrævet" },
            rangeLength: { message: "Feltværdiens længde er udenfor det tilladte område" },
            minLength: { message: "Feltværdien er for kort" },
            maxLength: { message: "Feltværdien er for lang" },
            pattern: { message: "Feltværtien matcher ikke det definerede mønster" },
            range: { message: "Feltværdien er udenfor det definerede område" },
            min: { message: "Feltværdien er for lav" },
            max: { message: "Feltværdien er for høj" }
        }
    };

}(jsGrid, jQuery));
