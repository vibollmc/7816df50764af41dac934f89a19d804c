using System;
using Microsoft.EntityFrameworkCore.Metadata;
using Microsoft.EntityFrameworkCore.Migrations;

namespace Football.Show.Migrations
{
    public partial class DbInitial : Migration
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
                    UpdatedAt = table.Column<DateTime>(nullable: true),
                    DeletedAt = table.Column<DateTime>(nullable: true),
                    Name = table.Column<string>(maxLength: 50, nullable: false),
                    Slug = table.Column<string>(maxLength: 50, nullable: false),
                    IsMenu = table.Column<bool>(nullable: false)
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
                    CreatedAt = table.Column<DateTime>(nullable: true),
                    UpdatedAt = table.Column<DateTime>(nullable: true),
                    DeletedAt = table.Column<DateTime>(nullable: true),
                    BaseLink = table.Column<string>(maxLength: 500, nullable: false),
                    FromPage = table.Column<int>(nullable: true),
                    ToPage = table.Column<int>(nullable: true),
                    Finished = table.Column<int>(nullable: true),
                    IsFinished = table.Column<bool>(nullable: false),
                    IsCircle = table.Column<bool>(nullable: false)
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
                    UpdatedAt = table.Column<DateTime>(nullable: true),
                    DeletedAt = table.Column<DateTime>(nullable: true),
                    ServerFtp = table.Column<string>(maxLength: 50, nullable: false),
                    UserName = table.Column<string>(maxLength: 50, nullable: false),
                    Password = table.Column<string>(maxLength: 50, nullable: false),
                    Patch = table.Column<string>(maxLength: 50, nullable: false),
                    ServerUrl = table.Column<string>(maxLength: 50, nullable: false),
                    Port = table.Column<int>(nullable: false),
                    ServerName = table.Column<string>(maxLength: 50, nullable: false)
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
                    UpdatedAt = table.Column<DateTime>(nullable: true),
                    DeletedAt = table.Column<DateTime>(nullable: true),
                    Name = table.Column<string>(maxLength: 50, nullable: false),
                    Slug = table.Column<string>(maxLength: 50, nullable: false)
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
                    CreatedAt = table.Column<DateTime>(nullable: true),
                    UpdatedAt = table.Column<DateTime>(nullable: true),
                    DeletedAt = table.Column<DateTime>(nullable: true),
                    Title = table.Column<string>(maxLength: 100, nullable: false),
                    Slug = table.Column<string>(maxLength: 100, nullable: false),
                    MatchDate = table.Column<DateTime>(nullable: false),
                    Home = table.Column<string>(maxLength: 100, nullable: true),
                    Away = table.Column<string>(maxLength: 100, nullable: true),
                    HomeManager = table.Column<string>(maxLength: 100, nullable: true),
                    AwayManager = table.Column<string>(maxLength: 100, nullable: true),
                    Score = table.Column<string>(maxLength: 5, nullable: true),
                    Referee = table.Column<string>(maxLength: 100, nullable: true),
                    Competition = table.Column<string>(maxLength: 100, nullable: true),
                    Stadium = table.Column<string>(maxLength: 100, nullable: true),
                    HomePersonScored = table.Column<string>(maxLength: 300, nullable: true),
                    AwayPersonScored = table.Column<string>(maxLength: 300, nullable: true),
                    ImageName = table.Column<string>(maxLength: 300, nullable: true),
                    ImageServerId = table.Column<int>(nullable: true),
                    CategoryId = table.Column<int>(nullable: true)
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
                    CreatedAt = table.Column<DateTime>(nullable: true),
                    UpdatedAt = table.Column<DateTime>(nullable: true),
                    DeletedAt = table.Column<DateTime>(nullable: true),
                    MatchId = table.Column<int>(nullable: true),
                    Name = table.Column<string>(maxLength: 50, nullable: false),
                    Url = table.Column<string>(maxLength: 1000, nullable: false),
                    LinkType = table.Column<int>(nullable: true),
                    ClipType = table.Column<int>(nullable: true)
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
                    UpdatedAt = table.Column<DateTime>(nullable: true),
                    DeletedAt = table.Column<DateTime>(nullable: true),
                    MatchId = table.Column<int>(nullable: true),
                    Number = table.Column<int>(nullable: true),
                    Name = table.Column<string>(maxLength: 200, nullable: true),
                    Type = table.Column<int>(nullable: false),
                    IsSubstitution = table.Column<bool>(nullable: false)
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
                    TagId = table.Column<int>(nullable: false),
                    MatchId = table.Column<int>(nullable: false)
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
                    UpdatedAt = table.Column<DateTime>(nullable: true),
                    DeletedAt = table.Column<DateTime>(nullable: true),
                    Number = table.Column<int>(nullable: true),
                    Name = table.Column<string>(maxLength: 200, nullable: true),
                    Minutes = table.Column<int>(nullable: true),
                    FormationId = table.Column<int>(nullable: true),
                    MatchId = table.Column<int>(nullable: true),
                    Type = table.Column<int>(nullable: false)
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
                    table.ForeignKey(
                        name: "FK_Substitutions_Matchs_MatchId",
                        column: x => x.MatchId,
                        principalTable: "Matchs",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                });

            migrationBuilder.CreateIndex(
                name: "IX_Categories_Slug",
                table: "Categories",
                column: "Slug",
                unique: true);

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
                name: "IX_Matchs_Slug",
                table: "Matchs",
                column: "Slug",
                unique: true);

            migrationBuilder.CreateIndex(
                name: "IX_Substitutions_FormationId",
                table: "Substitutions",
                column: "FormationId",
                unique: true);

            migrationBuilder.CreateIndex(
                name: "IX_Substitutions_MatchId",
                table: "Substitutions",
                column: "MatchId");

            migrationBuilder.CreateIndex(
                name: "IX_TagAssignments_TagId",
                table: "TagAssignments",
                column: "TagId");

            migrationBuilder.CreateIndex(
                name: "IX_Tags_Slug",
                table: "Tags",
                column: "Slug",
                unique: true);
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
