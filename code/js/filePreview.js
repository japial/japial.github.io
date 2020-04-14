function filePreview(input, imgid) 
{
  if (input.files && input.files[0]) { 
    var reader = new FileReader();
    reader.onload = function (e){
       $('#’+imgid).attr('src', e.target.result);
    };
    
    reader.readAsDataURL(input.files[0]);
  }
}
