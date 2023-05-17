<form action="/sendMessage " method="post">
    @csrf
    <button type="submit" class="btn" style="background-color: hotpink;float: right;margin-bottom: 5px;"  > send notification  </button>
    <input class="form-control" id="" name="title" placeholder="notificaton title">
    <input class="form-control" id="" name="body" placeholder="notificaton body">
</form>
