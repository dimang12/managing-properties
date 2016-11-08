/**
 * Created by dimang12 on 11/21/14.
 */

(function( $ ) {
    $.fn.gallery = function ( options ){
        var ele = $( this );

        /*
         * add event click to image
         */
        ele.find( 'img' ).each(function( k,v ){
            $( v ).on( 'click', function(){
                $( ele ).imagePreview(options, $(v) );
            });
        });
    }

    $.fn.imagePreview = function( options , current) {

        /*
         * declare params
         */
        var ele = $( this );
        var markup = ele.html();
        var iPreview = $( '.image-preview' );
        var wrap = $( '<div class="i-wrap" />' );
        var btnClose = $( '<span class="i-close"><i class="glyphicon glyphicon-remove"></i> close</span>' );
        var btnNext = $( '<nav class="i-next">Next <i class="glyphicon glyphicon-chevron-right"></i></nav>' );
        var btnPrev = $( '<nav class="i-prev"><i class="glyphicon glyphicon-chevron-left"></i> Previous</nav>' );
        var thumb = $( '<div class="i-thumb-wrap"/>' );
        var img = $( '<div class="img" />' );
        var loading = $( '<span class="loading hidden" />' );
        var selected = null;

        /*
         * imagePreview defaults – added as a property on our plugin function.
         */
        var defaults = {
            closeButton: true,
            nextPrev: true,
            thumbnail:true,
            escape:true
        }

        /*
         *  Extend our default options with those provided.
         *  Note that the first argument to extend is an empty
         *  object – this is to keep from overriding our "defaults" object.
         */
        var opts = $.extend( {}, defaults, options );

        /*
         *
         */
        ele.find( 'img' ).each(function( k,v ){
            var src =$( v ).attr( 'src' );
            a = $( '<a href="#" />' )
                    .css( 'background-image','url('+ src +')' )
                    .attr( 'data-src' , src.replace('/thumbnail', '' )  )
                ;
            curSrc = current.attr( 'src' );
            if( src == curSrc ){ selected = a; }
            
            //event on click thumbnail
            a.on( 'click', function(){
                var src = $( this ).attr( 'data-src' );
                $( thumb ).find( 'a' ).removeClass( 'cur' );
                $( this ).addClass( 'cur' )
                $( loading ).removeClass( 'hidden' );
                $( img ).addClass( 'opacity-40' );

                //loading
                $( '<img src="'+ src +'">' ).load( function() {
                    $( img ).html( '' ).append( this );
                    var h = $( this ).height() , w = $( this ).width();
                    var winH = $( window ).height() , winW = $( window ).width();

                    $( img ).css( 'top', (winH - h)/2).css( 'left', (winW - w)/2 );
                    $( loading ).addClass( 'hidden' );
                    $( img ).removeClass( 'opacity-40' );
                });
            });
            a.appendTo( thumb );
        });

        /*
         * add event on click to next
         */
        $( btnNext ).on( 'click', function(){
            if(thumb.find( '.cur' ).is( ':last-child' )){
                $( thumb ).find( 'a' ).first().trigger( 'click' );
                return false;
            }
            thumb.find( '.cur' ).next().trigger( 'click' );
        } );

        $( btnPrev).on( 'click', function(){
            if(thumb.find( '.cur').is( ':first-child' )){
                $( thumb ).find( 'a' ).last().trigger( 'click' );
                return false;
            }
            thumb.find( '.cur' ).prev().trigger( 'click' );
        } );

        $( btnClose ).on( 'click', function(){
            wrap.fadeOut( function(){ $( this ).remove(); });
        } );

        //esc key to close wrap
        $(document).bind( 'keyup' ,function(e) {
            if ( e.keyCode == 27 ) { $( btnClose ).trigger( 'click' ); }   // esc
        });

        /*
         * add image to wrap
         */
        wrap.append( img );

        /*
         * add close button
         */
        if( opts.closeButton==true ) wrap.append( btnClose );

        /*
         * add next button
         */
        if( opts.nextPrev==true ) wrap.append( btnPrev ).append( btnNext );


        /*
         * add thumbnail button
         */
        if( opts.thumbnail==true ) wrap.append( thumb );

        /*
         * add loading
         */
        wrap.append( loading );

        //add loading to center
        $( loading ).css( 'top', ($( window ).height()-6)/2).css( 'left' , ($( window).width()-6)/2 );

        /*
         * calculate fit hieght
         */
        wrap.height( $( window ).height() );


        iPreview.html( wrap );

        /*
         * set  clicked
         */
        $( selected ).trigger( 'click' );

        return this;
    };
}( jQuery ));