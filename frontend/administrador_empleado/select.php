<?php
        
            $stmt = $connect->prepare("SELECT * FROM asistencia");
            $stmt->execute();
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {
                    extract($row);
                    ?>
            <option value="<?php echo $idasi; ?>"><?php echo $nomas; ?></option>
                    <?php
                }
        ?>
            ?>