<?php

namespace FileNote;



use Illuminate\Support\ServiceProvider;

class NoteServiceProvide extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/fieldnote.php', 'fieldnote'
        );

    }

    public function boot(){

      $timestamp = date('Y_m_d_His');
      $this->publishes([
          __DIR__.'/database/migrations/create_field_notes_table.php.stub' => database_path("/migrations/{$timestamp}_create_field_notes_table.php"),
      ]);

    }

}
