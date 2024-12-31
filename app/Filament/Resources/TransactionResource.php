<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Boarding House Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Booking Details')
                            ->schema([
                                Grid::make()
                                    ->schema([
                                        TextInput::make('code')
                                            ->numeric()
                                            ->required()
                                            ->columnSpanFull(),
                                        Select::make('boarding_house_id')
                                            ->relationship('boardingHouse', 'name')
                                            ->required(),
                                        Select::make('room_id')
                                            ->relationship('room', 'name')
                                            ->required(),
                                        DatePicker::make('start_date')
                                            ->required(),
                                        TextInput::make('duration')
                                            ->numeric()
                                            ->required(),
                                    ])
                            ]),
                        Tab::make('Customer Information')
                            ->schema([
                                TextInput::make('name')
                                    ->required(),
                                TextInput::make('email')
                                    ->email()
                                    ->required(),
                                TextInput::make('phone_number')
                                    ->required(),
                            ]),
                        Tab::make('Payment Information')
                            ->schema([
                                Select::make('payment_method')
                                    ->options([
                                        'down_payment' => 'Down Payment',
                                        'full_payment' => 'Full Payment'
                                    ]),
                                Select::make('payment_status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'paid' => 'Paid'
                                    ]),
                                TextInput::make('total_amount')
                                    ->numeric()
                                    ->prefix('IDR')
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        // Remove commas if any (clean up input)
                                        $set('total_amount', str_replace(',', '', $state));
                                    })
                                    ->placeholder('e.g., 50000'),
                            ])
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('boardingHouse.name'),
                Tables\Columns\TextColumn::make('room.name'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('payment_method'),
                Tables\Columns\TextColumn::make('payment_status'),
                Tables\Columns\TextColumn::make('total_amount'),
                Tables\Columns\TextColumn::make('transaction_date'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
