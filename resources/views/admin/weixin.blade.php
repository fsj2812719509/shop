<form action="formshow" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="text" name="text">
    <input type="file" name="media">
    <input type="submit" value="SUBMIT">
</form>