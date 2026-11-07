<?php

use App\Livewire\Aset\Form as AsetForm;
use App\Livewire\Aset\Index as AsetIndex;
use App\Livewire\Aset\Show as AsetShow;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/aset');

Route::get('/aset', AsetIndex::class)->name('aset.index');

Route::get('/aset/create', AsetForm::class)->name('aset.create');

Route::get('/aset/{aset}', AsetShow::class)->name('aset.show');

Route::get('/aset/{aset}/edit', AsetForm::class)->name('aset.edit');
