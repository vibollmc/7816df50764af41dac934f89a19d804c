using Microsoft.EntityFrameworkCore.Migrations;
using System;
using System.Collections.Generic;

namespace HighLights.Web.Migrations
{
    public partial class UpdateMatchTable : Migration
    {
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.AddColumn<string>(
                name: "Away",
                table: "Matchs",
                maxLength: 100,
                nullable: true);

            migrationBuilder.AddColumn<string>(
                name: "AwayManager",
                table: "Matchs",
                maxLength: 100,
                nullable: true);

            migrationBuilder.AddColumn<string>(
                name: "Competition",
                table: "Matchs",
                maxLength: 100,
                nullable: true);

            migrationBuilder.AddColumn<string>(
                name: "Home",
                table: "Matchs",
                maxLength: 100,
                nullable: true);

            migrationBuilder.AddColumn<string>(
                name: "HomeManager",
                table: "Matchs",
                maxLength: 100,
                nullable: true);

            migrationBuilder.AddColumn<string>(
                name: "Referee",
                table: "Matchs",
                maxLength: 100,
                nullable: true);

            migrationBuilder.AddColumn<string>(
                name: "Score",
                table: "Matchs",
                maxLength: 5,
                nullable: true);

            migrationBuilder.AddColumn<string>(
                name: "Stadium",
                table: "Matchs",
                maxLength: 100,
                nullable: true);
        }

        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropColumn(
                name: "Away",
                table: "Matchs");

            migrationBuilder.DropColumn(
                name: "AwayManager",
                table: "Matchs");

            migrationBuilder.DropColumn(
                name: "Competition",
                table: "Matchs");

            migrationBuilder.DropColumn(
                name: "Home",
                table: "Matchs");

            migrationBuilder.DropColumn(
                name: "HomeManager",
                table: "Matchs");

            migrationBuilder.DropColumn(
                name: "Referee",
                table: "Matchs");

            migrationBuilder.DropColumn(
                name: "Score",
                table: "Matchs");

            migrationBuilder.DropColumn(
                name: "Stadium",
                table: "Matchs");
        }
    }
}
