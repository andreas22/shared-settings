<?php Orchestra\Site::set('header::add-button', true); ?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Created By</th>
            <th>Modified By</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @if ($apps->isEmpty())
        <tr>
            <td colspan="4">No records</td>
        </tr>
        @else
        @foreach ($apps as $app)
        <tr>
            <td><?php echo $app->id; ?></td>
            <td><?php echo $app->name; ?></td>
            <td>
                <?php echo $app->createdBy->fullname; ?>
                <div style="font-size: 10px"><?php echo $app->created_at; ?></div>
            </td>
            <td>
                <?php echo $app->modifiedBy->fullname; ?>
                <div style="font-size: 10px"><?php echo $app->updated_at; ?></div>
            </td>
            <td>
                <div class="btn-group">
                    <a href="<?php echo handles("orchestra/foundation::resources/apiusers/edit/{$app->id}"); ?>"
                       class="btn btn-mini btn-warning">Edit</a>
                    <a href="<?php echo handles("orchestra/foundation::resources/apiusers/delete/{$app->id}"); ?>"
                       class="btn btn-mini btn-danger">Delete</a>
                </div>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>