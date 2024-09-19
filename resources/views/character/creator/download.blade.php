
<div class="col-12 text-center">
   <h3> Congrats! Here is your new character. </h3>
   <b> Make sure to save it now! </b> 
</div>

<div class="col-12 text-center mb-3">
    <a class="btn btn-primary" href="{{ $image }}" download="character">Download</a>
</div>

<!--- The main "image" stacked together, without input we always show the first layer of each group.--->
<div class="col-12 text-center" style="background-color:grey;">
    <img src="{{ $image }}" class="w-100 n-auto" style="max-width:900px;" />
</div>


