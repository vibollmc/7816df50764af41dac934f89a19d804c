using Microsoft.EntityFrameworkCore.Metadata;
using Microsoft.EntityFrameworkCore.Migrations;
using System;
using System.Collections.Generic;

namespace HighLights.Web.Migrations
{
    public partial class InitDatabase : Migration
    {
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.CreateTable(
                name: "Categories",
                columns: table => new
                {
                    Id = table.Column<int>(nullable: false)
                        .Annotation("MySql:ValueGenerationStrategy", MySqlValueGenerationStrategy.IdentityColumn),
                    CreatedAt = table.Column<DateTime>(nullable: true),
                    DeletedAt = table.Column<DateTime>(nullable: true),
                    IsMenu = table.Column<bool>(nullable: false),
                    Name = table.Column<string>(maxLength: 200, nullable: false),
                    Slug = table.Column<string>(maxLength: 200, nullable: false),
                    UpdatedAt = table.Column<DateTime>(nullable: true)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_Categories", x => x.Id);
                });

            migrationBuilder.CreateTable(
                name: "CrawlLinks",
                columns: table => new
                {
                    Id = table.Column<int>(nullable: false)
                        .Annotation("MySql:ValueGenerationStrategy", MySqlValueGenerationStrategy.IdentityColumn),
                    BaseLink = table.Column<string>(nullable: true),
                    CreatedAt = table.Column<DateTime>(nullable: true),
                    DeletedAt = table.Column<DateTime>(nullable: true),
                    Finished = table.Column<int>(nullable: true),
                    FromPage = table.Column<int>(nullable: true),
                    IsCircle = table.Column<bool>(nullable: false),
                    IsFinished = table.Column<bool>(nullable: false),
                    ToPage = table.Column<int>(nullable: true),
                    UpdatedAt = table.Column<DateTime>(nullable: true)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_CrawlLinks", x => x.Id);
                });

            migrationBuilder.CreateTable(
                name: "ImageServers",
                columns: table => new
                {
                    Id = table.Column<int>(nullable: false)
                        .Annotation("MySql:ValueGenerationStrategy", MySqlValueGenerationStrategy.IdentityColumn),
                    CreatedAt = table.Column<DateTime>(nullable: true),
                    DeletedAt = table.Column<DateTime>(nullable: true),
                    Password = table.Column<string>(maxLength: 50, nullable: false),
                    Patch = table.Column<string>(maxLength: 50, nullable: false),
                    Port = table.Column<int>(nullable: false),
                    ServerFtp = table.Column<string>(maxLength: 50, nullable: false),
                    ServerName = table.Column<string>(maxLength: 50, nullable: false),
                    ServerUrl = table.Column<string>(maxLength: 50, nullable: false),
                    UpdatedAt = table.Column<DateTime>(nullable: true),
                    UserName = table.Column<string>(maxLength: 50, nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_ImageServers", x => x.Id);
                });

            migrationBuilder.CreateTable(
                name: "Tags",
                columns: table => new
                {
                    Id = table.Column<int>(nullable: false)
                        .Annotation("MySql:ValueGenerationStrategy", MySqlValueGenerationStrategy.IdentityColumn),
                    CreatedAt = table.Column<DateTime>(nullable: true),
                    DeletedAt = table.Column<DateTime>(nullable: true),
                    Name = table.Column<string>(maxLength: 50, nullable: false),
                    Slug = table.Column<string>(maxLength: 50, nullable: false),
                    UpdatedAt = table.Column<DateTime>(nullable: true)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_Tags", x => x.Id);
                });

            migrationBuilder.CreateTable(
                name: "Matchs",
                columns: table => new
                {
                    Id = table.Column<int>(nullable: false)
                        .Annotation("MySql:ValueGenerationStrategy", MySqlValueGenerationStrategy.IdentityColumn),
                    CategoryId = table.Column<int>(nullable: true),
                    CreatedAt = table.Column<DateTime>(nullable: true),
                    DeletedAt = table.Column<DateTime>(nullable: true),
                    ImageName = table.Column<string>(nullable: true),
                    ImageServerId = table.Column<int>(nullable: true),
                    MatchDate = table.Column<DateTime>(nullable: false),
                    Slug = table.Column<string>(maxLength: 500, nullable: false),
                    Title = table.Column<string>(maxLength: 500, nullable: false),
                    UpdatedAt = table.Column<DateTime>(nullable: true)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_Matchs", x => x.Id);
                    table.ForeignKey(
                        name: "FK_Matchs_Categories_CategoryId",
                        column: x => x.CategoryId,
                        principalTable: "Categories",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_Matchs_ImageServers_ImageServerId",
                        column: x => x.ImageServerId,
                        principalTable: "ImageServers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                });

            migrationBuilder.CreateTable(
                name: "Clips",
                columns: table => new
                {
                    Id = table.Column<int>(nullable: false)
                        .Annotation("MySql:ValueGenerationStrategy", MySqlValueGenerationStrategy.IdentityColumn),
                    ClipType = table.Column<int>(nullable: true),
                    CreatedAt = table.Column<DateTime>(nullable: true),
                    DeletedAt = table.Column<DateTime>(nullable: true),
                    LinkType = table.Column<int>(nullable: true),
                    MatchId = table.Column<int>(nullable: true),
                    Name = table.Column<string>(maxLength: 50, nullable: false),
                    UpdatedAt = table.Column<DateTime>(nullable: true),
                    Url = table.Column<string>(maxLength: 1000, nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_Clips", x => x.Id);
                    table.ForeignKey(
                        name: "FK_Clips_Matchs_MatchId",
                        column: x => x.MatchId,
                        principalTable: "Matchs",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                });

            migrationBuilder.CreateTable(
                name: "Formations",
                columns: table => new
                {
                    Id = table.Column<int>(nullable: false)
                        .Annotation("MySql:ValueGenerationStrategy", MySqlValueGenerationStrategy.IdentityColumn),
                    CreatedAt = table.Column<DateTime>(nullable: true),
                    DeletedAt = table.Column<DateTime>(nullable: true),
                    MatchId = table.Column<int>(nullable: true),
                    Name = table.Column<string>(nullable: true),
                    Number = table.Column<int>(nullable: true),
                    UpdatedAt = table.Column<DateTime>(nullable: true)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_Formations", x => x.Id);
                    table.ForeignKey(
                        name: "FK_Formations_Matchs_MatchId",
                        column: x => x.MatchId,
                        principalTable: "Matchs",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                });

            migrationBuilder.CreateTable(
                name: "TagAssignments",
                columns: table => new
                {
                    MatchId = table.Column<int>(nullable: false),
                    TagId = table.Column<int>(nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_TagAssignments", x => new { x.MatchId, x.TagId });
                    table.ForeignKey(
                        name: "FK_TagAssignments_Matchs_MatchId",
                        column: x => x.MatchId,
                        principalTable: "Matchs",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Cascade);
                    table.ForeignKey(
                        name: "FK_TagAssignments_Tags_TagId",
                        column: x => x.TagId,
                        principalTable: "Tags",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Cascade);
                });

            migrationBuilder.CreateTable(
                name: "Substitutions",
                columns: table => new
                {
                    Id = table.Column<int>(nullable: false)
                        .Annotation("MySql:ValueGenerationStrategy", MySqlValueGenerationStrategy.IdentityColumn),
                    CreatedAt = table.Column<DateTime>(nullable: true),
                    DeletedAt = table.Column<DateTime>(nullable: true),
                    FormationId = table.Column<int>(nullable: true),
                    Minutes = table.Column<int>(nullable: true),
                    Name = table.Column<string>(nullable: true),
                    Number = table.Column<int>(nullable: true),
                    UpdatedAt = table.Column<DateTime>(nullable: true)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_Substitutions", x => x.Id);
                    table.ForeignKey(
                        name: "FK_Substitutions_Formations_FormationId",
                        column: x => x.FormationId,
                        principalTable: "Formations",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                });

            migrationBuilder.CreateIndex(
                name: "IX_Clips_MatchId",
                table: "Clips",
                column: "MatchId");

            migrationBuilder.CreateIndex(
                name: "IX_Formations_MatchId",
                table: "Formations",
                column: "MatchId");

            migrationBuilder.CreateIndex(
                name: "IX_Matchs_CategoryId",
                table: "Matchs",
                column: "CategoryId");

            migrationBuilder.CreateIndex(
                name: "IX_Matchs_ImageServerId",
                table: "Matchs",
                column: "ImageServerId");

            migrationBuilder.CreateIndex(
                name: "IX_Substitutions_FormationId",
                table: "Substitutions",
                column: "FormationId",
                unique: true);

            migrationBuilder.CreateIndex(
                name: "IX_TagAssignments_TagId",
                table: "TagAssignments",
                column: "TagId");
        }

        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropTable(
                name: "Clips");

            migrationBuilder.DropTable(
                name: "CrawlLinks");

            migrationBuilder.DropTable(
                name: "Substitutions");

            migrationBuilder.DropTable(
                name: "TagAssignments");

            migrationBuilder.DropTable(
                name: "Formations");

            migrationBuilder.DropTable(
                name: "Tags");

            migrationBuilder.DropTable(
                name: "Matchs");

            migrationBuilder.DropTable(
                name: "Categories");

            migrationBuilder.DropTable(
                name: "ImageServers");
        }
    }
}
