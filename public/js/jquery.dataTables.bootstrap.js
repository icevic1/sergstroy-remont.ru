//http://datatables.net/plug-ins/pagination#bootstrap
/*$.extend( true, $.fn.dataTable.defaults, {
	"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
	"sPaginationType": "bootstrap",
	"oLanguage": {
		"sLengthMenu": "Display _MENU_ records"
	}
} );


// API method to get paging information 
$.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings )
{
    return {
        "iStart":         oSettings._iDisplayStart,
        "iEnd":           oSettings.fnDisplayEnd(),
        "iLength":        oSettings._iDisplayLength,
        "iTotal":         oSettings.fnRecordsTotal(),
        "iFilteredTotal": oSettings.fnRecordsDisplay(),
        "iPage":          Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
        "iTotalPages":    Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
    };
}
 
// Bootstrap style pagination control 
$.extend( $.fn.dataTableExt.oPagination, {
    "bootstrap": {
        "fnInit": function( oSettings, nPaging, fnDraw ) {
            var oLang = oSettings.oLanguage.oPaginate;
            var fnClickHandler = function ( e ) {
                e.preventDefault();
                if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
                    fnDraw( oSettings );
                }
            };
 
            $(nPaging).addClass('pagination').append(
                '<ul>'+
                    '<li class="prev disabled"><a href="#"><i class="icon-double-angle-left"></i></a></li>'+
                    '<li class="next disabled"><a href="#"><i class="icon-double-angle-right"></i></a></li>'+
                '</ul>'
            );
            var els = $('a', nPaging);
            $(els[0]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
            $(els[1]).bind( 'click.DT', { action: "next" }, fnClickHandler );
        },
 
        "fnUpdate": function ( oSettings, fnDraw ) {
            var iListLength = 5;
            var oPaging = oSettings.oInstance.fnPagingInfo();
            var an = oSettings.aanFeatures.p;
            var i, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);
 
            if ( oPaging.iTotalPages < iListLength) {
                iStart = 1;
                iEnd = oPaging.iTotalPages;
            }
            else if ( oPaging.iPage <= iHalf ) {
                iStart = 1;
                iEnd = iListLength;
            } else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
                iStart = oPaging.iTotalPages - iListLength + 1;
                iEnd = oPaging.iTotalPages;
            } else {
                iStart = oPaging.iPage - iHalf + 1;
                iEnd = iStart + iListLength - 1;
            }
 
            for ( i=0, iLen=an.length ; i<iLen ; i++ ) {
                // Remove the middle elements
                $('li:gt(0)', an[i]).filter(':not(:last)').remove();
 
                // Add the new list items and their event handlers
                for ( j=iStart ; j<=iEnd ; j++ ) {
                    sClass = (j==oPaging.iPage+1) ? 'class="active"' : '';
                    $('<li '+sClass+'><a href="#">'+j+'</a></li>')
                        .insertBefore( $('li:last', an[i])[0] )
                        .bind('click', function (e) {
                            e.preventDefault();
                            oSettings._iDisplayStart = (parseInt($('a', this).text(),10)-1) * oPaging.iLength;
                            fnDraw( oSettings );
                        } );
                }
 
                // Add / remove disabled classes from the static elements
                if ( oPaging.iPage === 0 ) {
                    $('li:first', an[i]).addClass('disabled');
                } else {
                    $('li:first', an[i]).removeClass('disabled');
                }
 
                if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
                    $('li:last', an[i]).addClass('disabled');
                } else {
                    $('li:last', an[i]).removeClass('disabled');
                }
            }
        }
    }
} );*/

// Default class modification 
	$.extend( $.fn.dataTableExt.oStdClasses, {
		"sWrapper": "dataTables_wrapper form-inline"
	} );
	 
	 
	// API method to get paging information 
	$.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings )
	{
		return {
			"iStart":         oSettings._iDisplayStart,
			"iEnd":           oSettings.fnDisplayEnd(),
			"iLength":        oSettings._iDisplayLength,
			"iTotal":         oSettings.fnRecordsTotal(),
			"iFilteredTotal": oSettings.fnRecordsDisplay(),
			"iPage":          Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
			"iTotalPages":    Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
		};
	}
	 $.fn.dataTableExt.oApi.fnReloadAjax = function (oSettings, sNewSource, myParams ) {
		if ( oSettings.oFeatures.bServerSide ) {
			oSettings.aoServerParams = [];
			oSettings.aoServerParams.push({"sName": "user",
				"fn": function (aoData) {
					for (var i=0;i<myParams.length;i++){
					aoData.push( {"name" : myParams[i][0], "value" : myParams[i][1]});
				 }
			 }});
			 this.fnClearTable(oSettings);
			 this.fnDraw();
			 
			 return;
		}
	};
	$.extend( $.fn.dataTableExt.oPagination, {
		"bootstrap": {
			"fnInit": function( oSettings, nPaging, fnDraw ) {
				var oLang = oSettings.oLanguage.oPaginate;
				var fnClickHandler = function ( e ) {
					e.preventDefault();
					if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
						fnDraw( oSettings );
					}
				};
	 
				$(nPaging).addClass('pagination pagination-right').append(
					'<ul>' +
						'<li class="prev disabled"><a href="#">&larr; ' + oLang.sFirst + '</a></li>' +
						'<li class="prev disabled"><a href="#">&larr; '+oLang.sPrevious+'</a></li>'+
						'<li class="next disabled"><a href="#">' + oLang.sNext + ' &rarr; </a></li>' +
						'<li class="next disabled"><a href="#">' + oLang.sLast + ' &rarr; </a></li>' +
					'</ul>'
				);
				var els = $('a', nPaging);
				$(els[0]).bind('click.DT', { action: "first" }, fnClickHandler);
				$(els[1]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
				$(els[2]).bind('click.DT', { action: "next" }, fnClickHandler);
				$(els[3]).bind('click.DT', { action: "last" }, fnClickHandler);
			},
	 
			"fnUpdate": function ( oSettings, fnDraw ) {
				var iListLength = 5;
				var oPaging = oSettings.oInstance.fnPagingInfo();
				var an = oSettings.aanFeatures.p;
				var i, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);
	 
				if ( oPaging.iTotalPages < iListLength) {
					iStart = 1;
					iEnd = oPaging.iTotalPages;
				}
				else if ( oPaging.iPage <= iHalf ) {
					iStart = 1;
					iEnd = iListLength;
				} else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
					iStart = oPaging.iTotalPages - iListLength + 1;
					iEnd = oPaging.iTotalPages;
				} else {
					iStart = oPaging.iPage - iHalf + 1;
					iEnd = iStart + iListLength - 1;
				}
	 
				for ( i=0, iLen=an.length ; i<iLen ; i++ ) {
					// Remove the middle elements
					$('li:gt(1)', an[i]).filter(':not(.next)').remove();
	 
					// Add the new list items and their event handlers
					for ( j=iStart ; j<=iEnd ; j++ ) {
				sClass = (j==oPaging.iPage+1) ? 'class="active"' : '';
						$('<li '+sClass+'><a href="#">'+j+'</a></li>')
							.insertBefore( $('li.next:first', an[i])[0] )
							.bind('click', function (e) {
								e.preventDefault();
								oSettings._iDisplayStart = (parseInt($('a', this).text(),10)-1) * oPaging.iLength;
								fnDraw( oSettings );
							} );
					}
	 
					// Add / remove disabled classes from the static elements
					if ( oPaging.iPage === 0 ) {
						$('li.prev', an[i]).addClass('disabled');
					} else {
						$('li.prev', an[i]).removeClass('disabled');
					}
	 
					if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
						$('li.next', an[i]).addClass('disabled');
					} else {
						$('li.next', an[i]).removeClass('disabled');
					}
				}
			}
		}
	} );