using MySqlConnector;

var builder = WebApplication.CreateBuilder(args);
builder.Services.AddCors(options =>
    options.AddDefaultPolicy(p => p.AllowAnyOrigin().AllowAnyHeader().AllowAnyMethod()));

var app = builder.Build();
app.UseCors();

var connectionString = builder.Configuration.GetConnectionString("Fitlife")
    ?? "Server=127.0.0.1;Database=fitlife;User ID=root;Password=;";

app.MapGet("/api/health", () =>
    Results.Ok(new { status = "online", sistema = "Fitlife Studio API" }));

app.MapGet("/api/estatisticas", async () =>
{
    await using var conn = new MySqlConnection(connectionString);
    await conn.OpenAsync();

    async Task<int> CountAsync(string expression)
    {
        await using var cmd = new MySqlCommand($"SELECT COUNT(*) FROM {expression}", conn);
        return Convert.ToInt32(await cmd.ExecuteScalarAsync());
    }

    return Results.Ok(new
    {
        alunos = await CountAsync("usuarios WHERE perfil='aluno'"),
        professores = await CountAsync("usuarios WHERE perfil='professor'"),
        aulas = await CountAsync("aulas"),
        agendamentos = await CountAsync("agendamentos WHERE status='confirmado'")
    });
});

app.Run();
