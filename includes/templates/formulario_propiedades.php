<fieldset>
    <legend>Información General</legend>
    <label for='titulo'>Titulo:</label>
    <input type='text' id='titulo' name='titulo' placeholder='Titulo propiedad'
        value="<?php echo s($propiedad->titulo); ?>">
    <label for='precio'>Precio:</label>
    <input type='number' id='precio' name='precio' placeholder='Precio propiedad'
        value="<?php echo s($propiedad->precio); ?>">
    <label for='imagen'>Imagen:</label>
    <input type='file' id='imagen' name='imagen' accept='image/jpge,image/png'>
    <label for='descripcion'>Descripción:</label>
    <textarea name='descripcion' id='descripcion' cols='30' rows='10'> <?php echo s($propiedad->descripcion);
?></textarea>
</fieldset>

<fieldset>
    <legend>Informacion Propiedad</legend>
    <label for='habitaciones'>Habitaciones:</label>
    <input type='number' id='habitaciones' name='habitaciones' placeholder='Ej: 3' min='1' max='9'
        value="<?php echo s($propiedad->habitaciones); ?>">
    <label for='wc'>Baños:</label>
    <input type='number' id='wc' placeholder='Ej: 3' name='wc' min='1' max='9' value="<?php echo s($propiedad->wc); ?>">
    <label for='estacionamiento'>Estacionamiento:</label>
    <input type='number' id='estacionamiento' name='estacionamiento' placeholder='Ej: 3' min='1' max='9'
        value="<?php echo s($propiedad->estacionamiento); ?>">
</fieldset>

<fieldset>
    <legend>Vendedor</legend>
</fieldset>
<br>