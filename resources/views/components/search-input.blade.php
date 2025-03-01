<form method="GET" action="">
    <div class="input-group">
        <input type="text" name="search" id="navbar-search-input"
               aria-label="search" aria-describedby="search"
               placeholder="Rechercher..."
               value="{{ request("search") ?? '' }}"
               class="form-control">
        <div class="input-group-btn">
            <button type="submit" class="btn btn-primary"><i
                    class="fas fa-search"></i></button>
        </div>
    </div>
</form>
