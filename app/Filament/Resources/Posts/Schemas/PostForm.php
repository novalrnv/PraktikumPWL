<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use App\Models\Category;

class PostForm
{
    public  static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //Section 1 - post details
                Section::make("Post Details")
                    ->Description("Fill in the details of the post")
                    // -> icon(Heroicon::RocketLaunch)
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        //Grouping fields into 2 columns
                        Group::make([
                            TextInput::make("title")
                                ->rules('required | min:5 | max:10')
                                ->validationMessages([
                                    "min" => 'Title at least 5 characters',
                                ]),
                            TextInput::make("slug")
                                ->rules('required', 'min:3')
                                ->unique()
                                ->validationMessages([
                                    "unique" => 'Slug must be unique'
                                ]),
                            Select::make("category_id")
                                ->relationship("category", "name")
                                ->options(Category::all()->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                            ColorPicker::make("color"),
                        ])->columns(2),

                        MarkdownEditor::make("content"),
                    ])->columnSpan(2),

                //Grouping fields into 2 columns
                Group::make([

                    //Section 2 - image
                    Section::make("Image Upload")
                        ->icon('heroicon-o-photo')
                        ->schema([
                            FileUpload::make("image")
                                ->disk("public")
                                ->directory("posts")
                                ->required(),
                        ]),

                    //Section 3 - meta
                    Section::make("Meta Information")
                        ->icon('heroicon-o-cog-6-tooth')
                        ->schema([
                            Select::make("tags")
                                ->multiple()
                                ->relationship("tags", "name")
                                ->searchable()
                                ->preload(),
                            Checkbox::make("published"),
                            DateTimePicker::make("published_at"),
                        ]),
                ])->columnSpan(1),
            ])->columns(3);
    }
}
