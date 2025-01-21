<!DOCTYPE html>

<html>
    <body>
        <table>
            <tbody>
                <?php foreach($parts as $part):?>  
                <tr>
                    <td><?php echo $part['name']; ?></td>
                    <td><?php echo $part['price']; ?></td>
                </tr>
                <?php endforeach;?>

            </tbody>
        </table>
    </body>
</html>
