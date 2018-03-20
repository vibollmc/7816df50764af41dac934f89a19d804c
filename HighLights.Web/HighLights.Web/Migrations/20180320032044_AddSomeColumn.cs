using Microsoft.EntityFrameworkCore.Migrations;
using System;
using System.Collections.Generic;

namespace HighLights.Web.Migrations
{
    public partial class AddSomeColumn : Migration
    {
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.AddColumn<int>(
                name: "Type",
                table: "Substitutions",
                nullable: false,
                defaultValue: 0);

            migrationBuilder.AlterColumn<string>(
                name: "ImageName",
                table: "Matchs",
                maxLength: 300,
                nullable: true,
                oldClrType: typeof(string),
                oldNullable: true);

            migrationBuilder.AddColumn<string>(
                name: "AwayPersonScored",
                table: "Matchs",
                maxLength: 300,
                nullable: true);

            migrationBuilder.AddColumn<string>(
                name: "HomePersonScored",
                table: "Matchs",
                maxLength: 300,
                nullable: true);

            migrationBuilder.AddColumn<bool>(
                name: "IsSubstitution",
                table: "Formations",
                nullable: false,
                defaultValue: false);

            migrationBuilder.AddColumn<int>(
                name: "Type",
                table: "Formations",
                nullable: false,
                defaultValue: 0);
        }

        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropColumn(
                name: "Type",
                table: "Substitutions");

            migrationBuilder.DropColumn(
                name: "AwayPersonScored",
                table: "Matchs");

            migrationBuilder.DropColumn(
                name: "HomePersonScored",
                table: "Matchs");

            migrationBuilder.DropColumn(
                name: "IsSubstitution",
                table: "Formations");

            migrationBuilder.DropColumn(
                name: "Type",
                table: "Formations");

            migrationBuilder.AlterColumn<string>(
                name: "ImageName",
                table: "Matchs",
                nullable: true,
                oldClrType: typeof(string),
                oldMaxLength: 300,
                oldNullable: true);
        }
    }
}
