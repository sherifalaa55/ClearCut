use Illuminate\Database\Migrations\Migration;

class SetupRequestLogsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Creates the request logs table
        Schema::create(\Config::get('clearcut.table_name'), function ($table) {
            $table->increments('id');
            $table->string("uuid");
            $table->text("payload");
            $table->text("request_headers");
            $table->text("response")->nullable();
            $table->integer("response_status")->nullable();
            $table->string("method");
            $table->integer("duration")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(\Config::get('clearcut.table_name'));
    }

}
