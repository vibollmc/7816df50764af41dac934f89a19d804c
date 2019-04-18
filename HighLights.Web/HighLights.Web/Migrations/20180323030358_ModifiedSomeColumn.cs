using Microsoft.EntityFrameworkCore.Migrations;

namespace HighLights.Web.Migrations
{
    public partial class ModifiedSomeColumn : Migration
    {
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.AddColumn<int>(
                name: "MatchId",
                table: "Substitutions",
                nullable: true);

            migrationBuilder.CreateIndex(
                name: "IX_Substitutions_MatchId",
                table: "Substitutions",
                column: "MatchId");

            migrationBuilder.AddForeignKey(
                name: "FK_Substitutions_Matchs_MatchId",
                table: "Substitutions",
                column: "MatchId",
                principalTable: "Matchs",
                principalColumn: "Id",
                onDelete: ReferentialAction.Restrict);
        }

        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropForeignKey(
                name: "FK_Substitutions_Matchs_MatchId",
                table: "Substitutions");

            migrationBuilder.DropIndex(
                name: "IX_Substitutions_MatchId",
                table: "Substitutions");

            migrationBuilder.DropColumn(
                name: "MatchId",
                table: "Substitutions");
        }
    }
}
