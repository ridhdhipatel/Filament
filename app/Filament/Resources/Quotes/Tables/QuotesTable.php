<?php

namespace App\Filament\Resources\Quotes\Tables;

use App\Http\Controllers\HomeController;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;

class QuotesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('name')->label('Customer Name')->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('phone')->label('Phone')->searchable(),
                TextColumn::make('address')->label('Address')->tooltip(fn($record) => $record->address)->searchable(),
                TextColumn::make('service.name')->label('Service')->badge(),
                TextColumn::make('duration')->label('Duration (hrs)'),
                TextColumn::make('price')->label('Price')->money('usd'),
                TextColumn::make('status')->label('Status')->badge()
                    ->colors([
                        'primary' => 'pending',
                        'success' => 'approved',
                        'warning' => 'scheduled',
                        'danger'  => 'rejected',
                        'info'    => 'invoiced',
                    ]),
                TextColumn::make('booking_date')->label('Booking Date')->dateTime('Y-m-d H:i')->sortable(),
                TextColumn::make('created_at')->label('Created At')->dateTime('Y-m-d H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'scheduled' => 'Scheduled', 'invoiced' => 'Invoiced'])->label('Status'),

                SelectFilter::make('service_id')->relationship('service', 'name')->label('Service'),

                Filter::make('booking_date')->schema([
                    DatePicker::make('booking_date')->label('Booking Date')
                ])->query(function ($query, array $data) {
                    return $query->when(
                        $data['booking_date'],
                        fn($q, $date) => $q->whereDate('booking_date', $date)
                    );
                })->label('Booking Date'),

                Filter::make('created_at')->schema([
                    DatePicker::make('created_at')->label('Created Date')
                ])->query(function ($query, array $data) {
                    return $query->when(
                        $data['created_at'],
                        fn($q, $date) => $q->whereDate('created_at', $date)
                    );
                })
                    ->label('Created Date'),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->action(function ($record, array $data) {
                        $record->status = 'approved';
                        if (!empty($data['price'])) {
                            $record->price = $data['price'];
                        }
                        $record->save();
                        (new HomeController())->sendToCustomer('Approved',$record->email,$record->name);
                    })
                    ->schema([
                        TextInput::make('price')->label('Price')->numeric()->required(),
                    ])
                    ->visible(fn($record) => $record->status === 'pending')
                    ->requiresConfirmation(),

                Action::make('reject')
                    ->label('Reject')
                    ->action(function ($record, array $data) {
                        $record->status = 'rejected';
                        $record->rejection_reason = $data['rejection_reason'];
                        $record->save();
                        (new HomeController())->sendToCustomer('Rejected',$record->email,$record->name);
                    })
                    ->schema([
                        TextInput::make('rejection_reason')->label('Reason')->required(),
                    ])
                    ->visible(fn($record) => $record->status === 'pending')
                    ->requiresConfirmation(),

                Action::make('schedule')
                    ->label('Mark Scheduled')
                    ->action(function ($record) {
                        $record->status = 'scheduled';
                        $record->save();
                        (new HomeController())->sendToCustomer('Scheduled',$record->email,$record->name);
                    })
                    ->visible(fn($record) => $record->status === 'approved'),

                Action::make('invoice')
                    ->label('Mark Invoiced')
                    ->action(function ($record) {
                        $record->status = 'invoiced';
                        $record->save();
                        (new HomeController())->sendToCustomer('Invoiced',$record->email,$record->name);
                    })
                    ->visible(fn($record) => $record->status === 'scheduled'),
            ])
            ->toolbarActions([])
            ->recordUrl(null);
    }
}
