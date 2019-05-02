using Microsoft.EntityFrameworkCore.Migrations;

namespace Football.Show.Migrations
{
    public partial class AddFormutionCardAndScore : Migration
    {
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.AddColumn<int>(
                name: "RedCard",
                table: "Substitutions",
                nullable: false,
                defaultValue: 0);

            migrationBuilder.AddColumn<int>(
                name: "Scores",
                table: "Substitutions",
                nullable: false,
                defaultValue: 0);

            migrationBuilder.AddColumn<int>(
                name: "YellowCard",
                table: "Substitutions",
                nullable: false,
                defaultValue: 0);

            migrationBuilder.AddColumn<int>(
                name: "RedCard",
                table: "Formations",
                nullable: false,
                defaultValue: 0);

            migrationBuilder.AddColumn<int>(
                name: "Scores",
                table: "Formations",
                nullable: false,
                defaultValue: 0);

            migrationBuilder.AddColumn<int>(
                name: "YellowCard",
                table: "Formations",
                nullable: false,
                defaultValue: 0);
        }

        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropColumn(
                name: "RedCard",
                table: "Substitutions");

            migrationBuilder.DropColumn(
                name: "Scores",
                table: "Substitutions");

            migrationBuilder.DropColumn(
                name: "YellowCard",
                table: "Substitutions");

            migrationBuilder.DropColumn(
                name: "RedCard",
                table: "Formations");

            migrationBuilder.DropColumn(
                name: "Scores",
                table: "Formations");

            migrationBuilder.DropColumn(
                name: "YellowCard",
                table: "Formations");
        }
    }
}
